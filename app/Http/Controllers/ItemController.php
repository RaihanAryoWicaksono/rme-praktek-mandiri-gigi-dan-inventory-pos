<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        // Get items with calculated current stock
        $items = Item::withSum('batches', 'stock')
            ->orderBy('name')
            ->get();
            
        return view('items.index', compact('items'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bahan,obat,produk',
            'unit_use' => 'required|string|max:50',
            'unit_purchase' => 'nullable|string|max:50',
            'conversion_factor' => 'required|integer|min:1',
            'min_stock_alert' => 'required|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Item::create($validated);

        return redirect()->route('items.index')->with('success', 'Item berhasil ditambahkan.');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:bahan,obat,produk',
            'unit_use' => 'required|string|max:50',
            'unit_purchase' => 'nullable|string|max:50',
            'conversion_factor' => 'required|integer|min:1',
            'min_stock_alert' => 'required|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Item berhasil diperbarui.');
    }

    public function destroy(Item $item)
    {
        if ($item->batches()->count() > 0 || $item->mutations()->count() > 0) {
            return redirect()->route('items.index')->with('error', 'Item tidak bisa dihapus karena sudah memiliki riwayat stok. Pertimbangkan untuk menonaktifkan statusnya.');
        }

        $item->delete();
        return redirect()->route('items.index')->with('success', 'Item berhasil dihapus.');
    }
}
