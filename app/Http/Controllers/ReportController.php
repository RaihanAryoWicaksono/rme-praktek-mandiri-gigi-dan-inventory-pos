<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Item;
use App\Models\ItemBatch;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $transactions = Transaction::with('patient')
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'completed')
            ->orderBy('date', 'desc')
            ->get();

        $expenses = \App\Models\Expense::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        $totalRevenue = $transactions->sum('total_amount');

        $totalExpense = $expenses->sum('amount');
        $netProfit = $totalRevenue - $totalExpense;

        if ($request->get('export') === 'excel') {
            $fileName = "Laporan_Keuangan_{$startDate}_sampai_{$endDate}.csv";
            $headers = [
                "Content-type" => "text/csv",
                "Content-Disposition" => "attachment; filename=$fileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ];
            
            $callback = function() use ($transactions, $expenses, $totalRevenue, $totalExpense, $netProfit, $startDate, $endDate) {
                $file = fopen('php://output', 'w');
                
                fputcsv($file, ['LAPORAN KEUANGAN KLINIK']);
                fputcsv($file, ['Periode:', $startDate . ' s/d ' . $endDate]);
                fputcsv($file, []);
                
                fputcsv($file, ['RINGKASAN']);
                fputcsv($file, ['Total Pendapatan', $totalRevenue]);
                fputcsv($file, ['Total Pengeluaran', $totalExpense]);
                fputcsv($file, ['Laba Bersih', $netProfit]);
                fputcsv($file, []);
                
                fputcsv($file, ['DATA PENDAPATAN']);
                fputcsv($file, ['Tanggal', 'No. Transaksi', 'Pasien', 'Subtotal', 'Metode Bayar', 'Total Akhir']);
                foreach ($transactions as $trx) {
                    fputcsv($file, [
                        $trx->date->format('Y-m-d'),
                        $trx->transaction_number,
                        $trx->patient->name ?? '',
                        $trx->subtotal,
                        strtoupper($trx->payment_method),
                        $trx->total_amount
                    ]);
                }
                
                fputcsv($file, []);
                
                fputcsv($file, ['DATA PENGELUARAN']);
                fputcsv($file, ['Tanggal', 'Nama Pengeluaran', 'Keterangan', 'Nominal']);
                foreach ($expenses as $exp) {
                    fputcsv($file, [
                        $exp->date,
                        $exp->name,
                        $exp->description,
                        $exp->amount
                    ]);
                }
                
                fclose($file);
            };
            return response()->stream($callback, 200, $headers);
        }

        if ($request->get('export') === 'pdf') {
            // For simple HTML to PDF, return print view directly
            return view('reports.print', compact('transactions', 'expenses', 'startDate', 'endDate', 'totalRevenue', 'totalExpense', 'netProfit'));
        }

        return view('reports.revenue', compact('transactions', 'expenses', 'startDate', 'endDate', 'totalRevenue', 'totalExpense', 'netProfit'));
    }

    public function stock()
    {
        $items = Item::withSum('batches', 'stock')
            ->orderBy('name')
            ->get();

        $expiredBatches = ItemBatch::with('item')
            ->where('stock', '>', 0)
            ->where('expiry_date', '<', Carbon::now())
            ->get();

        return view('reports.stock', compact('items', 'expiredBatches'));
    }
}
