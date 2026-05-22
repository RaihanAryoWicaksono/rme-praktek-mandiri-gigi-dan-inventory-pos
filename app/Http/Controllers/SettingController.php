<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Kunjungan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $kunjunganHariIni = Kunjungan::with(['patient', 'rekamMedis'])
            ->whereDate('tanggal_kunjungan', Carbon::today())
            ->orderBy('created_at')
            ->get();
        return view('settings.index', compact('settings', 'kunjunganHariIni'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'clinic_name' => 'required|string|max:255',
            'clinic_address' => 'required|string',
            'clinic_phone' => 'nullable|string',
            'expiry_alert_days' => 'required|integer|min:1',
            'min_stock_alert' => 'required|integer|min:0',
        ]);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil disimpan.');
    }
}
