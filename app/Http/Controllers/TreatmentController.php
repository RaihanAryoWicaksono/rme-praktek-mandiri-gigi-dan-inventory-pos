<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index()
    {
        $treatments = Treatment::orderBy('name')->get();
        return view('treatments.index', compact('treatments'));
    }

    public function create()
    {
        return view('treatments.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Treatment::create($validated);

        return redirect()->route('treatments.index')->with('success', 'Tindakan berhasil ditambahkan.');
    }

    public function edit(Treatment $treatment)
    {
        return view('treatments.edit', compact('treatment'));
    }

    public function update(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'base_price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $treatment->update($validated);

        return redirect()->route('treatments.index')->with('success', 'Tindakan berhasil diperbarui.');
    }

    public function destroy(Treatment $treatment)
    {
        if ($treatment->bomTemplates()->count() > 0) {
            return redirect()->route('treatments.index')->with('error', 'Tindakan tidak bisa dihapus karena memiliki resep BOM.');
        }

        $treatment->delete();
        return redirect()->route('treatments.index')->with('success', 'Tindakan berhasil dihapus.');
    }
}
