<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Input Stok Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-lg border border-white/40 shadow-xl overflow-hidden sm:rounded-2xl p-8">
                
                <form action="{{ route('stock.inbound.store') }}" method="POST" class="space-y-6" x-data="{ 
                    selectedItem: '',
                    items: {{ $items->toJson() }},
                    unitType: 'purchase',
                    get currentItem() {
                        return this.items.find(i => i.id == this.selectedItem) || null;
                    }
                }">
                    @csrf
                    
                    <div>
                        <x-input-label for="item_id" :value="__('Pilih Item')" />
                        <select id="item_id" name="item_id" x-model="selectedItem" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-white/50" required>
                            <option value="">-- Pilih Item --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} ({{ $item->type }})</option>
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('item_id')" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="currentItem">
                        <div>
                            <x-input-label for="quantity" :value="__('Jumlah')" />
                            <x-text-input id="quantity" name="quantity" type="number" step="0.01" class="mt-1 block w-full bg-white/50" required />
                            <div class="text-xs text-indigo-600 mt-1" x-show="unitType == 'purchase' && currentItem">
                                Estimasi Masuk: <span class="font-bold whitespace-nowrap" x-text="(($el.closest('form').querySelector('#quantity').value || 0) * currentItem.conversion_factor) + ' ' + currentItem.unit_use"></span>
                            </div>
                        </div>

                        <div>
                            <x-input-label for="unit_type" :value="__('Satuan Input')" />
                            <select id="unit_type" name="unit_type" x-model="unitType" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-white/50">
                                <template x-if="currentItem && currentItem.unit_purchase">
                                    <option value="purchase" x-text="'Satuan Beli (' + currentItem.unit_purchase + ')'"></option>
                                </template>
                                <option value="use" x-text="'Satuan Pakai (' + (currentItem ? currentItem.unit_use : 'pcs') + ')'"></option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="batch_number" :value="__('Nomor Batch / No. Faktur (Opsional)')" />
                            <x-text-input id="batch_number" name="batch_number" type="text" class="mt-1 block w-full bg-white/50" />
                        </div>

                        <div>
                            <x-input-label for="expiry_date" :value="__('Tanggal Kedaluwarsa')" />
                            <x-text-input id="expiry_date" name="expiry_date" type="date" class="mt-1 block w-full bg-white/50" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="reason" :value="__('Catatan / Keterangan')" />
                        <x-text-input id="reason" name="reason" type="text" class="mt-1 block w-full bg-white/50" placeholder="Contoh: Pengadaan Bulanan April" />
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('stock.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700">
                            {{ __('Simpan Stok Masuk') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
