<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemBatch;
use App\Models\StockMutation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockController extends Controller
{
    /**
     * Display the stock history and summary.
     */
    public function index()
    {
        $mutations = StockMutation::with(['item', 'batch'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('stock.index', compact('mutations'));
    }

    /**
     * Show form for new inbound stock.
     */
    public function inbound()
    {
        $items = Item::where('is_active', true)->orderBy('name')->get();
        return view('stock.inbound', compact('items'));
    }

    /**
     * Store inbound stock.
     */
    public function storeInbound(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'quantity' => 'required|numeric|min:0.01',
            'unit_type' => 'required|in:purchase,use',
            'batch_number' => 'nullable|string|max:100',
            'expiry_date' => 'nullable|date',
            'reason' => 'nullable|string|max:255',
        ]);

        $item = Item::findOrFail($validated['item_id']);
        
        // Calculate real quantity in "use units"
        $realQuantity = $validated['quantity'];
        if ($validated['unit_type'] === 'purchase') {
            $realQuantity = $validated['quantity'] * $item->conversion_factor;
        }

        DB::transaction(function () use ($item, $validated, $realQuantity) {
            // Create or update batch
            $batch = ItemBatch::create([
                'item_id' => $item->id,
                'batch_number' => $validated['batch_number'],
                'stock' => $realQuantity,
                'expiry_date' => $validated['expiry_date'],
            ]);

            // Record mutation
            StockMutation::create([
                'item_id' => $item->id,
                'item_batch_id' => $batch->id,
                'type' => 'in',
                'quantity' => $realQuantity,
                'reason' => $validated['reason'] ?? 'Stok Masuk Baru',
            ]);

            // Add Expense Automatically based on purchase_price (purchase_price is per purchase_unit)
            $expenseAmount = ($realQuantity / max(1, $item->conversion_factor)) * ($item->purchase_price ?? 0);
            if ($expenseAmount > 0) {
                \App\Models\Expense::create([
                    'date' => now()->toDateString(),
                    'name' => 'Restock: ' . $item->name,
                    'description' => 'Inbound stok ' . $realQuantity . ' ' . $item->unit_use,
                    'amount' => $expenseAmount,
                ]);
            }
        });

        return redirect()->route('stock.index')->with('success', 'Stok berhasil ditambahkan.');
    }

    /**
     * Show form for stock correction.
     */
    public function correction()
    {
        $items = Item::where('is_active', true)->with('batches')->get();
        return view('stock.correction', compact('items'));
    }

    /**
     * Store stock correction.
     */
    public function storeCorrection(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:items,id',
            'item_batch_id' => 'required|exists:item_batches,id',
            'type' => 'required|in:in,out',
            'quantity' => 'required|numeric|min:0.01',
            'reason' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $batch = ItemBatch::findOrFail($validated['item_batch_id']);
            
            if ($validated['type'] === 'out') {
                $batch->decrement('stock', $validated['quantity']);
            } else {
                $batch->increment('stock', $validated['quantity']);
            }

            StockMutation::create([
                'item_id' => $validated['item_id'],
                'item_batch_id' => $batch->id,
                'type' => 'correction',
                'quantity' => $validated['type'] === 'out' ? -$validated['quantity'] : $validated['quantity'],
                'reason' => $validated['reason'],
            ]);
        });

        return redirect()->route('stock.index')->with('success', 'Koreksi stok berhasil disimpan.');
    }
}
