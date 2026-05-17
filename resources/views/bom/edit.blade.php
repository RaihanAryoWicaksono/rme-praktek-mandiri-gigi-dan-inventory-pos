<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Atur BOM Template</h2>
    </x-slot>

    <div class="max-w-3xl space-y-5"
         x-data="{
            items: {{ $items->toJson() }},
            bomItems: {{ $bomTemplate->items->map(fn($i) => ['item_id' => $i->item_id, 'name' => $i->item->name, 'quantity' => $i->quantity, 'unit' => $i->unit])->toJson() }},
            selectedItem: '',
            addMaterial() {
                const item = this.items.find(i => i.id == this.selectedItem);
                if (item && !this.bomItems.find(i => i.item_id == item.id)) {
                    this.bomItems.push({ item_id: item.id, name: item.name, quantity: 1, unit: item.unit_use });
                    this.selectedItem = '';
                }
            },
            removeItem(index) { this.bomItems.splice(index, 1); }
         }">

        <div class="flex items-center gap-3">
            <a href="{{ route('treatments.bom.index', $bomTemplate->treatment_id) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $bomTemplate->name }}</h1>
                <p class="text-sm text-gray-500 mt-0.5">BOM Template — Atur bahan standar untuk tindakan ini</p>
            </div>
        </div>

        <form action="{{ route('bom.update', $bomTemplate) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Nama Template --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Nama Template</h2>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Template <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $bomTemplate->name) }}" required
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Daftar Bahan --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Daftar Bahan & Qty Standar</h2>

                {{-- Tambah Bahan --}}
                <div class="flex gap-2">
                    <select x-model="selectedItem"
                            class="flex-1 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Pilih bahan untuk ditambahkan...</option>
                        <template x-for="item in items" :key="item.id">
                            <option :value="item.id" x-text="item.name + ' (' + item.unit_use + ')'"></option>
                        </template>
                    </select>
                    <button type="button" @click="addMaterial"
                            class="px-4 py-2 bg-teal-600 text-white text-sm font-medium rounded-lg hover:bg-teal-700 transition-colors shrink-0">
                        + Tambah
                    </button>
                </div>

                {{-- List --}}
                <div class="space-y-2">
                    <template x-for="(bomItem, index) in bomItems" :key="bomItem.item_id">
                        <div class="flex items-center gap-3 bg-gray-50 px-4 py-3 rounded-lg border border-gray-100">
                            <p class="flex-1 text-sm font-medium text-gray-800" x-text="bomItem.name"></p>
                            <input type="hidden" :name="'items['+index+'][item_id]'" :value="bomItem.item_id">
                            <div class="flex items-center gap-2 w-36">
                                <input type="number" step="0.01"
                                       :name="'items['+index+'][quantity]'"
                                       x-model="bomItem.quantity"
                                       class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 text-right"
                                       required>
                                <span class="text-xs text-gray-500 shrink-0 w-10" x-text="bomItem.unit"></span>
                            </div>
                            <button type="button" @click="removeItem(index)"
                                    class="text-gray-300 hover:text-red-500 transition-colors p-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </template>

                    <div x-show="bomItems.length === 0"
                         class="py-10 text-center border-2 border-dashed border-gray-200 rounded-xl">
                        <p class="text-sm text-gray-400 italic">Belum ada bahan. Pilih dari dropdown di atas.</p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pb-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-teal-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-teal-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Template BOM
                </button>
                <a href="{{ route('treatments.bom.index', $bomTemplate->treatment_id) }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
