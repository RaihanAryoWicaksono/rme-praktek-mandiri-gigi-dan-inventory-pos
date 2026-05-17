<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('no_rm', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $patients = $query->latest()->paginate(15)->withQueryString();

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        $noRM = Patient::generateNoRM();
        return view('patients.create', compact('noRM'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap'   => 'required|string|max:100',
            'nik'            => 'nullable|string|size:16|unique:patients,nik',
            'tanggal_lahir'  => 'required|date|before:today',
            'jenis_kelamin'  => 'required|in:L,P',
            'golongan_darah' => 'required|in:A,B,AB,O,-',
            'alamat'         => 'required|string',
            'phone'          => 'required|string|max:15',
            'email'          => 'nullable|email|max:100',
            'pekerjaan'      => 'nullable|string|max:50',
            'alergi'         => 'nullable|string',
            'fotos'          => 'nullable|array',
            'fotos.*'        => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['no_rm']        = Patient::generateNoRM();
        $validated['name']         = $validated['nama_lengkap'];

        if ($request->hasFile('fotos')) {
            $validated['fotos'] = collect($request->file('fotos'))
                ->map(fn($file) => $file->store('foto-pasien', 'public'))
                ->values()
                ->all();
        }

        Patient::create($validated);

        return redirect()->route('patients.index')->with('success', 'Data pasien berhasil ditambahkan.');
    }

    public function show(Patient $patient)
    {
        $kunjungans = $patient->kunjungans()->with('rekamMedis')->latest('tanggal_kunjungan')->paginate(10);
        return view('patients.show', compact('patient', 'kunjungans'));
    }

    public function edit(Patient $patient)
    {
        return view('patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'nama_lengkap'   => 'required|string|max:100',
            'nik'            => 'nullable|string|size:16|unique:patients,nik,' . $patient->id,
            'tanggal_lahir'  => 'required|date|before:today',
            'jenis_kelamin'  => 'required|in:L,P',
            'golongan_darah' => 'required|in:A,B,AB,O,-',
            'alamat'         => 'required|string',
            'phone'          => 'required|string|max:15',
            'email'          => 'nullable|email|max:100',
            'pekerjaan'      => 'nullable|string|max:50',
            'alergi'         => 'nullable|string',
            'fotos'          => 'nullable|array',
            'fotos.*'        => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validated['name'] = $validated['nama_lengkap'];

        if ($request->hasFile('fotos')) {
            $existing = $patient->fotos ?? [];
            $new = collect($request->file('fotos'))
                ->map(fn($file) => $file->store('foto-pasien', 'public'))
                ->values()
                ->all();
            $validated['fotos'] = array_merge($existing, $new);
        } else {
            unset($validated['fotos']);
        }

        $patient->update($validated);

        return redirect()->route('patients.show', $patient)->with('success', 'Data pasien berhasil diperbarui.');
    }

    public function destroyFoto(Patient $patient, int $index)
    {
        $fotos = $patient->fotos ?? [];

        if (isset($fotos[$index])) {
            Storage::disk('public')->delete($fotos[$index]);
            array_splice($fotos, $index, 1);
            $patient->update(['fotos' => array_values($fotos)]);
        }

        return back()->with('success', 'Foto berhasil dihapus.');
    }

    public function destroy(Patient $patient)
    {
        if ($patient->transactions()->count() > 0) {
            return redirect()->route('patients.index')
                ->with('error', 'Pasien tidak bisa dihapus karena memiliki riwayat transaksi.');
        }

        foreach ($patient->fotos ?? [] as $path) {
            Storage::disk('public')->delete($path);
        }

        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Pasien berhasil dihapus.');
    }
}
