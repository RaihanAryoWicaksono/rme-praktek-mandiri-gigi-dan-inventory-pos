<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Transaksi:') }} {{ $transaction->transaction_number }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{ 
        showVoidModal: false, 
        voidUrl: '', 
        trxNumber: '',
        openModal(url, number) {
            this.voidUrl = url;
            this.trxNumber = number;
            this.showVoidModal = true;
        }
    }" 
    @open-void-modal.window="openModal($event.detail.url, $event.detail.number)">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-2 mb-6">
                @if($transaction->status !== 'cancelled')
                <button type="button" @click="$dispatch('open-void-modal', { url: '{{ route('transactions.void', $transaction) }}', number: '{{ addslashes($transaction->transaction_number) }}' })" class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 transition shadow-sm">BATALKAN TRANSAKSI</button>
                @endif
                <a href="{{ route('transactions.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition shadow-sm">Kembali</a>
                <a href="{{ route('transactions.print', $transaction) }}" target="_blank" class="px-4 py-2 bg-teal-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-teal-700 transition shadow-sm">Cetak Struk</a>
            </div>

            <div class="bg-white/70 backdrop-blur-lg border border-white/40 shadow-xl sm:rounded-2xl p-8 space-y-8">
                
                <!-- Header Info -->
                <div class="grid grid-cols-2 gap-8 border-b border-gray-100 pb-8">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Informasi Pasien</p>
                        <h3 class="text-2xl font-black text-gray-900">{{ $transaction->patient->name }}</h3>
                        <p class="text-sm font-mono text-teal-600 font-semibold">{{ $transaction->patient->no_rm }}</p>
                        <p class="text-gray-500 text-sm">{{ $transaction->patient->phone ?? '-' }}</p>
                    </div>
                    <div class="text-right">
                        @if($transaction->status === 'cancelled')
                            <div class="mb-2">
                                <span class="px-4 py-2 bg-red-100 text-red-700 font-black rounded-lg text-sm border-2 border-red-200 uppercase tracking-widest">TRANSAKSI DIBATALKAN</span>
                            </div>
                        @endif
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Waktu Transaksi</p>
                        <p class="text-xl font-bold text-gray-800">{{ $transaction->date->format('d/m/Y') }}</p>
                        <p class="text-sm text-gray-500">Dibuat: {{ $transaction->created_at->format('H:i') }}</p>
                    </div>
                </div>

                <!-- Items Table -->
                <div>
                    <h4 class="text-sm font-bold text-teal-600 mb-4 uppercase tracking-wider">Rincian Tindakan & Obat</h4>
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Deskripsi</th>
                                <th class="px-4 py-2 text-right">Qty</th>
                                <th class="px-4 py-2 text-right">Harga Satuan</th>
                                <th class="px-4 py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 italic font-mono">
                            @foreach($transaction->treatments as $tr)
                                <tr>
                                    <td class="px-4 py-3 font-bold text-teal-900">{{ $tr->treatment->name }}</td>
                                    <td class="px-4 py-3 text-right">1</td>
                                    <td class="px-4 py-3 text-right">Rp {{ number_format($tr->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-bold">Rp {{ number_format($tr->price, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                            @foreach($transaction->items->where('price', '>', 0) as $item)
                                <tr>
                                    <td class="px-4 py-3 text-gray-700">{{ $item->item->name }}</td>
                                    <td class="px-4 py-3 text-right">{{ $item->quantity }} {{ $item->item->unit_use }}</td>
                                    <td class="px-4 py-3 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right font-bold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="border-t-2 border-gray-900">
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-right font-bold text-gray-400">SUBTOTAL</td>
                                <td class="px-4 py-4 text-right text-xl font-bold">Rp {{ number_format($transaction->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-4 py-1 text-right font-bold text-gray-400">DISKON</td>
                                <td class="px-4 py-1 text-right text-lg font-bold text-red-500">- Rp {{ number_format($transaction->discount, 0, ',', '.') }}</td>
                            </tr>
                            <tr class="bg-teal-600 text-white">
                                <td colspan="3" class="px-4 py-4 text-right font-black text-lg">TOTAL AKHIR</td>
                                <td class="px-4 py-4 text-right text-3xl font-black">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Bahan yang terpotong (Not for receipt but for info) -->
                @if($transaction->items->where('price', 0)->count() > 0)
                <div class="mt-8 pt-8 border-t border-dashed border-gray-200">
                    <h4 class="text-xs font-bold text-gray-400 mb-4 uppercase tracking-widest">Alokasi Stok Bahan Terpotong</h4>
                    <div class="grid grid-cols-2 lg:grid-cols-3 gap-2">
                        @foreach($transaction->items->where('price', 0) as $usedItem)
                            <div class="bg-gray-50 p-2 rounded text-xs border border-gray-100">
                                <span class="font-bold text-gray-700">{{ $usedItem->item->name }}</span>: 
                                <span class="text-teal-600 font-bold">{{ $usedItem->quantity }} {{ $usedItem->item->unit_use }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
        
        <!-- Custom Void Modal -->
        <div x-show="showVoidModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/50 backdrop-blur-sm"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">
            <div @click.away="showVoidModal = false" class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform transition-all"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                <h3 class="text-xl font-black text-gray-900 mb-2">Konfirmasi Batal</h3>
                <p class="text-gray-600 mb-6">Apakah Anda yakin ingin membatalkan transaksi <span class="font-bold text-red-600" x-text="trxNumber"></span>? <br><br> <span class="text-xs text-red-500 italic">Stok akan dikembalikan otomatis.</span></p>
                <div class="flex justify-end space-x-3">
                    <button type="button" @click="showVoidModal = false" class="px-4 py-2 font-bold text-gray-500 hover:bg-gray-100 rounded-xl transition">Kembali</button>
                    <form :action="voidUrl" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="px-4 py-2 font-bold bg-red-600 text-white rounded-xl hover:bg-red-700 shadow-lg shadow-red-200 transition active:scale-95">Ya, Batalkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
