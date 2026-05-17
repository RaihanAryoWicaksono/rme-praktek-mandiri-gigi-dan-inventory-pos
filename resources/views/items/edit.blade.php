<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Item / Bahan</h2>
    </x-slot>

    <div class="max-w-3xl space-y-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('items.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Item / Bahan</h1>
        </div>

        <form action="{{ route('items.update', $item) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Informasi Item --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Informasi Item</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Item <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $item->name) }}" required autofocus
                           class="w-full px-3 py-2 text-sm border @error('name') border-red-400 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis <span class="text-red-500">*</span></label>
                        <select name="type" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="bahan"  @selected(old('type', $item->type) == 'bahan')>Bahan (Base Material)</option>
                            <option value="obat"   @selected(old('type', $item->type) == 'obat')>Obat</option>
                            <option value="produk" @selected(old('type', $item->type) == 'produk')>Produk / Retail</option>
                        </select>
                    </div>
                    <div class="flex items-end pb-0.5">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $item->is_active) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                            <span class="text-sm text-gray-700">Aktif (tampil di POS)</span>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Harga & Stok --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Harga & Stok</h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli (Rp)</label>
                        <input type="number" name="purchase_price" value="{{ old('purchase_price', $item->purchase_price) }}" min="0" step="1"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @error('purchase_price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual (Rp)</label>
                        <input type="number" name="selling_price" value="{{ old('selling_price', $item->selling_price) }}" min="0" step="1"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @error('selling_price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Min. Stok Peringatan <span class="text-red-500">*</span></label>
                        <input type="number" name="min_stock_alert" value="{{ old('min_stock_alert', $item->min_stock_alert) }}" min="0" required
                               class="w-full px-3 py-2 text-sm border @error('min_stock_alert') border-red-400 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @error('min_stock_alert') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Satuan & Konversi --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Satuan & Konversi</h2>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan Pakai <span class="text-red-500">*</span></label>
                        <input type="text" name="unit_use" value="{{ old('unit_use', $item->unit_use) }}" required
                               class="w-full px-3 py-2 text-sm border @error('unit_use') border-red-400 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <p class="mt-1 text-xs text-gray-400">Satuan saat digunakan ke pasien.</p>
                        @error('unit_use') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Satuan Beli</label>
                        <input type="text" name="unit_purchase" value="{{ old('unit_purchase', $item->unit_purchase) }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <p class="mt-1 text-xs text-gray-400">Satuan saat stok masuk.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rasio Konversi <span class="text-red-500">*</span></label>
                        <input type="number" name="conversion_factor" value="{{ old('conversion_factor', $item->conversion_factor) }}" min="1" required
                               class="w-full px-3 py-2 text-sm border @error('conversion_factor') border-red-400 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <p class="mt-1 text-xs text-amber-600">Mengubah nilai ini tidak merubah stok yang sudah ada.</p>
                        @error('conversion_factor') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pb-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-teal-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-teal-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('items.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
