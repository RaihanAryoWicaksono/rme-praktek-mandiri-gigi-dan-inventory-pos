<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\BomTemplate;
use App\Models\BomTemplateItem;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BomController extends Controller
{
    /**
     * List all BOM templates for a specific treatment.
     */
    public function index(Treatment $treatment)
    {
        $treatment->load('bomTemplates.items.item');
        $items = Item::where('is_active', true)->where('type', 'bahan')->orderBy('name')->get();
        return view('bom.index', compact('treatment', 'items'));
    }

    /**
     * Store a new BOM template.
     */
    public function store(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $treatment->bomTemplates()->create($validated);

        return redirect()->back()->with('success', 'Template BOM berhasil dibuat.');
    }

    /**
     * Show edit form for a BOM template.
     */
    public function edit(BomTemplate $bomTemplate)
    {
        $bomTemplate->load(['treatment', 'items.item']);
        $items = Item::where('is_active', true)->whereIn('type', ['bahan', 'obat'])->orderBy('name')->get();
        return view('bom.edit', compact('bomTemplate', 'items'));
    }

    /**
     * Update BOM template items.
     */
    public function update(Request $request, BomTemplate $bomTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'items' => 'nullable|array',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
        ]);

        DB::transaction(function () use ($bomTemplate, $validated) {
            $bomTemplate->update(['name' => $validated['name']]);
            
            // Sync items (Delete existing and recreate for simplicity in MVP)
            $bomTemplate->items()->delete();
            
            if (isset($validated['items'])) {
                foreach ($validated['items'] as $itemData) {
                    $item = Item::find($itemData['item_id']);
                    $bomTemplate->items()->create([
                        'item_id' => $itemData['item_id'],
                        'quantity' => $itemData['quantity'],
                        'unit' => $item->unit_use,
                    ]);
                }
            }
        });

        return redirect()->route('treatments.bom.index', $bomTemplate->treatment_id)
            ->with('success', 'Template BOM berhasil diperbarui.');
    }

    /**
     * Remove a BOM template.
     */
    public function destroy(BomTemplate $bomTemplate)
    {
        $treatmentId = $bomTemplate->treatment_id;
        $bomTemplate->delete();
        return redirect()->route('treatments.bom.index', $treatmentId)
            ->with('success', 'Template BOM berhasil dihapus.');
    }
}
