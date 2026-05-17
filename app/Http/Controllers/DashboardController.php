<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Patient;
use App\Models\Item;
use App\Models\ItemBatch;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        
        // Stats
        $revenueToday = Transaction::whereDate('date', $today)->where('status', 'completed')->sum('total_amount');
        $transactionsToday = Transaction::whereDate('date', $today)->where('status', 'completed')->count();
        $newPatientsToday = Patient::whereDate('created_at', $today)->count();
        
        $expensesThisMonth = \App\Models\Expense::whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');
        
        // Settings for alerts
        $settings = Setting::all()->pluck('value', 'key');
        $lowStockThreshold = (int) ($settings['min_stock_alert'] ?? 10);
        $expiryRange = (int) ($settings['expiry_alert_days'] ?? 30);
        
        // Low Stock Items
        $lowStockItems = Item::withSum('batches', 'stock')
            ->where('is_active', true)
            ->get()
            ->filter(function($item) use ($lowStockThreshold) {
                // Return true if stock is somehow less than threshold OR stock is null (0) and threshold > 0
                return ($item->batches_sum_stock ?? 0) < $lowStockThreshold;
            });
        // Near Expired Batches
        $nearExpiredBatches = ItemBatch::with('item')
            ->where('stock', '>', 0)
            ->where('expiry_date', '<=', Carbon::now()->addDays($expiryRange))
            ->where('expiry_date', '>=', Carbon::now())
            ->orderBy('expiry_date')
            ->get();

        // Expired Items
        $expiredBatches = ItemBatch::with('item')
            ->where('stock', '>', 0)
            ->where('expiry_date', '<', Carbon::now())
            ->get();

        // Chart Data (Last 7 days revenue)
        $chartLabels = [];
        $chartData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartLabels[] = $date->format('d M');
            $chartData[] = Transaction::whereDate('date', $date)->where('status', 'completed')->sum('total_amount');
        }

        $recentTransactions = Transaction::with('patient')->orderBy('created_at', 'desc')->take(5)->get();

        return view('dashboard', compact(
            'revenueToday', 
            'transactionsToday', 
            'newPatientsToday', 
            'expensesThisMonth',
            'lowStockItems', 
            'nearExpiredBatches', 
            'expiredBatches',
            'chartLabels',
            'chartData',
            'recentTransactions'
        ));
    }
}
