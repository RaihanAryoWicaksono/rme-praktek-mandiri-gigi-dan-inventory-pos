<?php

namespace App\Http\Controllers;

use App\Models\RekamMedis;
use App\Models\Kunjungan;
use App\Models\Patient;
use Illuminate\Http\Request;

class RekamMedisController extends Controller
{
    public function index(Request $request)
    {
        $query = RekamMedis::with('patient', 'kunjungan');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', fn($q) => $q->where('nama_lengkap', 'like', "%{$search}%")
                ->orWhere('no_rm', 'like', "%{$search}%"));
        }

        $rekamMedis = $query->latest()->paginate(15)->withQueryString();

        return view('rekam-medis.index', compact('rekamMedis'));
    }

    public function create(Request $request)
    {
        $kunjungan = null;
        if ($request->filled('kunjungan_id')) {
            $kunjungan = Kunjungan::with('patient')->findOrFail($request->kunjungan_id);
        }
        $kunjungans = Kunjungan::with('patient')
            ->whereDoesntHave('rekamMedis')
            ->orderByDesc('tanggal_kunjungan')
            ->get();

        return view('rekam-medis.create', compact('kunjungans', 'kunjungan'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kunjungan_id'    => 'required|exists:kunjungans,id|unique:rekam_medis,kunjungan_id',
            'diagnosis'       => 'required|string',
            'tindakan'        => 'required|string',
            'resep'           => 'nullable|string',
            'catatan'         => 'nullable|string',
            'odontogram_data' => 'nullable|string',
            'foto'            => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $kunjungan = Kunjungan::findOrFail($validated['kunjungan_id']);

        $odontogram = null;
        if (!empty($validated['odontogram_data'])) {
            $odontogram = json_decode($validated['odontogram_data'], true);
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('rekam-medis', 'public');
        }

        RekamMedis::create([
            'kunjungan_id'    => $kunjungan->id,
            'patient_id'      => $kunjungan->patient_id,
            'diagnosis'       => $validated['diagnosis'],
            'tindakan'        => $validated['tindakan'],
            'resep'           => $validated['resep'] ?? null,
            'catatan'         => $validated['catatan'] ?? null,
            'odontogram_data' => $odontogram,
            'foto'            => $fotoPath,
        ]);

        $kunjungan->update(['status' => 'selesai']);

        return redirect()->route('rekam-medis.index')
            ->with('success', 'Rekam medis berhasil disimpan.');
    }

    public function show(RekamMedis $rekamMedis)
    {
        $rekamMedis->load('patient', 'kunjungan');
        return view('rekam-medis.show', compact('rekamMedis'));
    }

    public function edit(RekamMedis $rekamMedis)
    {
        $rekamMedis->load('kunjungan.patient');
        return view('rekam-medis.edit', compact('rekamMedis'));
    }

    public function update(Request $request, RekamMedis $rekamMedis)
    {
        $validated = $request->validate([
            'diagnosis'       => 'required|string',
            'tindakan'        => 'required|string',
            'resep'           => 'nullable|string',
            'catatan'         => 'nullable|string',
            'odontogram_data' => 'nullable|string',
        ]);

        $odontogram = null;
        if (!empty($validated['odontogram_data'])) {
            $odontogram = json_decode($validated['odontogram_data'], true);
        }

        $rekamMedis->update([
            'diagnosis'       => $validated['diagnosis'],
            'tindakan'        => $validated['tindakan'],
            'resep'           => $validated['resep'] ?? null,
            'catatan'         => $validated['catatan'] ?? null,
            'odontogram_data' => $odontogram,
        ]);

        return redirect()->route('rekam-medis.show', $rekamMedis)
            ->with('success', 'Rekam medis berhasil diperbarui.');
    }

    public function destroy(RekamMedis $rekamMedis)
    {
        $rekamMedis->delete();
        return redirect()->route('rekam-medis.index')
            ->with('success', 'Rekam medis berhasil dihapus.');
    }
}
