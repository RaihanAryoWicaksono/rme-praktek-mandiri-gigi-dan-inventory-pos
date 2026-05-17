<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Patient;
use Illuminate\Http\Request;

class KunjunganController extends Controller
{
    public function index(Request $request)
    {
        $query = Kunjungan::with('patient');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('no_rm', 'like', "%{$search}%"));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_kunjungan', $request->tanggal);
        } else {
            $query->whereDate('tanggal_kunjungan', today());
        }

        $kunjungans = $query->latest()->paginate(15)->withQueryString();

        return view('kunjungan.index', compact('kunjungans'));
    }

    public function create(Request $request)
    {
        $patient = null;
        if ($request->filled('patient_id')) {
            $patient = Patient::findOrFail($request->patient_id);
        }
        $patients = Patient::orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'no_rm']);
        return view('kunjungan.create', compact('patients', 'patient'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'        => 'required|exists:patients,id',
            'tanggal_kunjungan' => 'required|date',
            'keluhan_utama'     => 'required|string',
            'anamnesis'         => 'nullable|string',
            'tekanan_darah'     => 'nullable|string|max:10',
            'nadi'              => 'nullable|string|max:10',
            'status'            => 'required|in:antrian,sedang_diperiksa,selesai',
        ]);

        $kunjungan = Kunjungan::create($validated);

        return redirect()->route('kunjungan.show', $kunjungan)
            ->with('success', 'Kunjungan berhasil ditambahkan.');
    }

    public function show(Kunjungan $kunjungan)
    {
        $kunjungan->load('patient', 'rekamMedis');
        return view('kunjungan.show', compact('kunjungan'));
    }

    public function edit(Kunjungan $kunjungan)
    {
        $patients = Patient::orderBy('nama_lengkap')->get(['id', 'nama_lengkap', 'no_rm']);
        return view('kunjungan.edit', compact('kunjungan', 'patients'));
    }

    public function update(Request $request, Kunjungan $kunjungan)
    {
        $validated = $request->validate([
            'patient_id'        => 'required|exists:patients,id',
            'tanggal_kunjungan' => 'required|date',
            'keluhan_utama'     => 'required|string',
            'anamnesis'         => 'nullable|string',
            'tekanan_darah'     => 'nullable|string|max:10',
            'nadi'              => 'nullable|string|max:10',
            'status'            => 'required|in:antrian,sedang_diperiksa,selesai',
        ]);

        $kunjungan->update($validated);

        return redirect()->route('kunjungan.show', $kunjungan)
            ->with('success', 'Kunjungan berhasil diperbarui.');
    }

    public function destroy(Kunjungan $kunjungan)
    {
        $kunjungan->delete();
        return redirect()->route('kunjungan.index')->with('success', 'Kunjungan berhasil dihapus.');
    }
}
