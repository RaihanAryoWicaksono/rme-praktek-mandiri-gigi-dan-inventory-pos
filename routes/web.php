<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\BomController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\RekamMedisController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $kunjunganHariIni = \App\Models\Kunjungan::with('patient')
        ->whereDate('tanggal_kunjungan', \Carbon\Carbon::today())
        ->orderBy('created_at')
        ->get();
    return view('welcome', compact('kunjunganHariIni'));
});

Route::get('/api/antrian-status', function () {
    $kunjungan = \App\Models\Kunjungan::with('patient')
        ->whereDate('tanggal_kunjungan', \Carbon\Carbon::today())
        ->orderBy('created_at')
        ->get();

    $latest = $kunjungan->last();

    $patients = $kunjungan->values()->map(fn($k, $i) => [
        'no'         => $i + 1,
        'nama'       => $k->patient->display_name,
        'status'     => $k->status,
        'updated_at' => $k->updated_at->toISOString(),
    ]);

    return response()->json([
        'total'             => $kunjungan->count(),
        'menunggu'          => $kunjungan->where('status', 'antrian')->count(),
        'diperiksa'         => $kunjungan->where('status', 'sedang_diperiksa')->count(),
        'selesai'           => $kunjungan->where('status', 'selesai')->count(),
        'patients'          => $patients,
        'latest_created_at' => $latest?->created_at->toISOString(),
        'latest_updated_at' => $latest?->updated_at->toISOString(),
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Master Data
    Route::resource('patients', PatientController::class);
    Route::delete('patients/{patient}/foto/{index}', [PatientController::class, 'destroyFoto'])->name('patients.foto.destroy');
    Route::resource('treatments', TreatmentController::class);
    Route::resource('items', ItemController::class);

    // Inventory & Stock
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/inbound', [StockController::class, 'inbound'])->name('stock.inbound');
    Route::post('/stock/inbound', [StockController::class, 'storeInbound'])->name('stock.inbound.store');
    Route::get('/stock/correction', [StockController::class, 'correction'])->name('stock.correction');
    Route::post('/stock/correction', [StockController::class, 'storeCorrection'])->name('stock.correction.store');

    // BOM (Bill of Materials) Templates
    Route::get('/treatments/{treatment}/bom', [BomController::class, 'index'])->name('treatments.bom.index');
    Route::post('/treatments/{treatment}/bom', [BomController::class, 'store'])->name('treatments.bom.store');
    Route::delete('/bom/{bomTemplate}', [BomController::class, 'destroy'])->name('bom.destroy');
    Route::get('/bom/{bomTemplate}/edit', [BomController::class, 'edit'])->name('bom.edit');
    Route::put('/bom/{bomTemplate}', [BomController::class, 'update'])->name('bom.update');

    // POS & Transactions
    Route::get('/pos', [TransactionController::class, 'create'])->name('pos.index');
    Route::post('/pos', [TransactionController::class, 'store'])->name('pos.store');
    Route::get('/pos/patient/{patient}/rekam-medis', [TransactionController::class, 'getPatientRekamMedis'])->name('pos.patient.rekam-medis');
    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/transactions/{transaction}/void', [TransactionController::class, 'void'])->name('transactions.void');
    Route::get('/transactions/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');

    // Rekam Medis
    Route::resource('kunjungan', KunjunganController::class);
    Route::resource('rekam-medis', RekamMedisController::class)
        ->parameters(['rekam-medis' => 'rekamMedis']);

    // Reports
    Route::get('/reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
    Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
