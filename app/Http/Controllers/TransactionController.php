<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Treatment;
use App\Models\Item;
use App\Models\RekamMedis;
use App\Models\Transaction;
use App\Models\TransactionTreatment;
use App\Models\TransactionItem;
use App\Models\ItemBatch;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('patient')->orderBy('created_at', 'desc')->get();
        return view('transactions.index', compact('transactions'));
    }

    public function void(Transaction $transaction)
    {
        if ($transaction->status === 'cancelled') {
            return back()->with('error', 'Transaksi sudah dibatalkan sebelumnya.');
        }

        return DB::transaction(function () use ($transaction) {
            // 1. Revert Stock
            $mutations = StockMutation::where('reference_transaction_id', $transaction->id)->get();
            
            foreach ($mutations as $mutation) {
                if ($mutation->item_batch_id) {
                    $batch = ItemBatch::find($mutation->item_batch_id);
                    if ($batch) {
                        // Re-add the quantity (mutation quantity was negative for 'out')
                        $batch->increment('stock', abs($mutation->quantity));
                        
                        // Record reversal mutation
                        StockMutation::create([
                            'item_id' => $mutation->item_id,
                            'item_batch_id' => $batch->id,
                            'type' => 'in',
                            'quantity' => abs($mutation->quantity),
                            'reason' => 'PEMBATALAN TRX ' . $transaction->transaction_number,
                            'reference_transaction_id' => $transaction->id,
                        ]);
                    }
                }
            }

            // 2. Update Status
            $transaction->update(['status' => 'cancelled']);

            return redirect()->route('transactions.index')->with('success', 'Transaksi ' . $transaction->transaction_number . ' Berhasil Dibatalkan.');
        });
    }

    public function getPatientRekamMedis(Patient $patient)
    {
        $rekamMedis = RekamMedis::whereHas('kunjungan', fn($q) =>
            $q->where('patient_id', $patient->id)
        )->with('kunjungan')->latest()->first();

        if (!$rekamMedis) {
            return response()->json(['found' => false]);
        }

        // Parse tindakan lines that start with "- " and match against treatment master
        $lines = collect(explode("\n", $rekamMedis->tindakan ?? ''))
            ->map(fn($l) => trim(ltrim(trim($l), '-')))
            ->filter()
            ->values();

        $matchedIds = Treatment::where('is_active', true)
            ->whereIn('name', $lines)
            ->pluck('id');

        return response()->json([
            'found'               => true,
            'diagnosis'           => $rekamMedis->diagnosis,
            'tindakan'            => $rekamMedis->tindakan,
            'resep'               => $rekamMedis->resep,
            'matched_treatment_ids' => $matchedIds,
        ]);
    }

    public function create()
    {
        $patients = Patient::orderBy('name')->get();
        // Load treatments with their BOM templates and items (include current stock sum)
        $treatments = Treatment::where('is_active', true)
            ->with(['bomTemplates.items.item' => function($q) {
                $q->withSum('batches', 'stock');
            }])
            ->orderBy('name')
            ->get();
        // Include current stock sum for items
        $items = Item::where('is_active', true)
            ->withSum('batches', 'stock')
            ->orderBy('name')
            ->get();

        $recentTransactions = Transaction::with('patient')->orderBy('created_at', 'desc')->take(5)->get();
        return view('pos.index', compact('patients', 'treatments', 'items', 'recentTransactions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'date' => 'required|date',

            'payment_method' => 'required|in:cash,qris',
            'amount_paid' => 'required|numeric|min:0',
            'treatments' => 'required|array|min:1',
            'treatments.*.id' => 'required|exists:treatments,id',
            'treatments.*.price' => 'required|numeric|min:0',
            'items' => 'nullable|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        return DB::transaction(function () use ($validated) {
            // 1. Calculate totals
            $subtotalTreatments = collect($validated['treatments'])->sum('price');
            $subtotalItems = collect($validated['items'] ?? [])->sum(fn($i) => $i['price'] * $i['quantity']);
            $subtotal = $subtotalTreatments + $subtotalItems;
            $totalAmount = $subtotal;

            // 2. Create Transaction Header
            $transaction = Transaction::create([
                'transaction_number' => 'TRX-' . strtoupper(uniqid()),
                'patient_id' => $validated['patient_id'],
                'date' => $validated['date'],
                'subtotal' => $subtotal,

                'total_amount' => $totalAmount,
                'payment_method' => $validated['payment_method'],
                'amount_paid' => $validated['amount_paid'],
                'change_amount' => max(0, $validated['amount_paid'] - $totalAmount),
                'status' => 'completed',
            ]);

            // 3. Save Treatments
            foreach ($validated['treatments'] as $tData) {
                TransactionTreatment::create([
                    'transaction_id' => $transaction->id,
                    'treatment_id' => $tData['id'],
                    'price' => $tData['price'],
                ]);
            }

            // 4. Save Items and Deduct Stock
            if (isset($validated['items'])) {
                foreach ($validated['items'] as $iData) {
                    $item = Item::findOrFail($iData['item_id']);
                    
                    TransactionItem::create([
                        'transaction_id' => $transaction->id,
                        'item_id' => $iData['item_id'],
                        'item_type' => $item->type,
                        'quantity' => $iData['quantity'],
                        'price' => $iData['price'],
                        'subtotal' => $iData['price'] * $iData['quantity'],
                    ]);

                    // DEDUCT STOCK
                    $remainingToDeduct = $iData['quantity'];
                    
                    // Get batches ordered by expiry date (earliest first), nulls last
                    $batches = ItemBatch::where('item_id', $item->id)
                        ->where('stock', '>', 0)
                        ->orderByRaw('expiry_date IS NULL, expiry_date ASC')
                        ->get();

                    foreach ($batches as $batch) {
                        if ($remainingToDeduct <= 0) break;

                        $deductFromBatch = min($batch->stock, $remainingToDeduct);
                        
                        $batch->decrement('stock', $deductFromBatch);
                        
                        StockMutation::create([
                            'item_id' => $item->id,
                            'item_batch_id' => $batch->id,
                            'type' => 'out',
                            'quantity' => -$deductFromBatch,
                            'reason' => 'Transaksi POS ' . $transaction->transaction_number,
                            'reference_transaction_id' => $transaction->id,
                        ]);

                        $remainingToDeduct -= $deductFromBatch;
                    }

                    // If still remaining (stock not enough), we still record it but batch_id might be null or we track negative?
                    // PRD doesn't explicitly say what to do if stock is empty. 
                    // Usually in POS we allow it but show warning or record as negative.
                    // For now, if stock is not enough, we stop deducting but the transaction item record remains.
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil disimpan.',
                'redirect' => route('transactions.show', $transaction->id)
            ]);
        });
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['patient', 'treatments.treatment', 'items.item']);
        return view('transactions.show', compact('transaction'));
    }

    public function print(Transaction $transaction)
    {
        $transaction->load(['patient', 'treatments.treatment', 'items.item']);
        return view('transactions.print', compact('transaction'));
    }
}
