<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Koreksi Stok Manual') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/70 backdrop-blur-lg border border-white/40 shadow-xl overflow-hidden sm:rounded-2xl p-8">
                
                <form action="{{ route('stock.correction.store') }}" method="POST" class="space-y-6" x-data="{ 
                    selectedItemId: '',
                    items: {{ $items->toJson() }},
                    get selectedItem() {
                        return this.items.find(i => i.id == this.selectedItemId) || null;
                    }
                }">
                    @csrf
                    
                    <div>
                        <x-input-label for="item_id" :value="__('Pilih Item')" />
                        <select id="item_id" name="item_id" x-model="selectedItemId" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-white/50" required>
                            <option value="">-- Pilih Item --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div x-show="selectedItem">
                        <x-input-label for="item_batch_id" :value="__('Pilih Batch / Stok')" />
                        <select id="item_batch_id" name="item_batch_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-white/50" required>
                            <option value="">-- Pilih Batch --</option>
                            <template x-if="selectedItem">
                                <template x-for="batch in selectedItem.batches" :key="batch.id">
                                    <option :value="batch.id" x-text="(batch.batch_number || 'Tanpa Batch') + ' | Sisa: ' + batch.stock + ' ' + selectedItem.unit_use + (batch.expiry_date ? ' | Exp: ' + batch.expiry_date : '')"></option>
                                </template>
                            </template>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6" x-show="selectedItemId">
                        <div>
                            <x-input-label for="type" :value="__('Jenis Koreksi')" />
                            <select id="type" name="type" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm bg-white/50" required>
                                <option value="out">Pengurangan (Rusak/Hilang)</option>
                                <option value="in">Penambahan (Koreksi Admin)</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="quantity" :value="__('Jumlah Koreksi')" />
                            <x-text-input id="quantity" name="quantity" type="number" step="0.01" class="mt-1 block w-full bg-white/50" required />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="reason" :value="__('Alasan Koreksi')" />
                        <x-text-input id="reason" name="reason" type="text" class="mt-1 block w-full bg-white/50" required placeholder="Contoh: Barangnya pecah / Salah input sebelumnya" />
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('stock.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Batal</a>
                        <x-primary-button class="bg-yellow-600 hover:bg-yellow-700">
                            {{ __('Simpan Koreksi') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
