<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Transaksi</h2>
    </x-slot>

    <div class="space-y-5" x-data="{
        showVoidModal: false, voidUrl: '', trxNumber: '',
        openModal(url, number) { this.voidUrl = url; this.trxNumber = number; this.showVoidModal = true; }
    }" @open-void-modal.window="openModal($event.detail.url, $event.detail.number)">

        <div class="main-content-card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Riwayat Transaksi</h1>
                    <p class="text-sm text-gray-500 mt-1">Semua transaksi kasir</p>
                </div>
                <a href="{{ route('pos.index') }}"
                   class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 7h16a1 1 0 011 1v10a1 1 0 01-1 1H4a1 1 0 01-1-1V8a1 1 0 011-1z" />
                    </svg>
                    Buka POS
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
            @endif

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm datatable" data-empty-message="Belum ada riwayat transaksi.">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">No. Transaksi</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Pasien</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Total</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                                <th class="col-aksi text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($transactions as $trx)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 font-mono font-bold text-gray-800 text-xs">{{ $trx->transaction_number }}</td>
                                    <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $trx->date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $trx->patient->name }}</td>
                                    <td class="px-4 py-3 font-bold text-teal-600">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        @if($trx->status === 'cancelled')
                                            <span class="inline-flex text-xs font-semibold px-2 py-0.5 rounded-full bg-red-100 text-red-700">Dibatalkan</span>
                                        @else
                                            <span class="inline-flex text-xs font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Selesai</span>
                                        @endif
                                    </td>
                                    <td class="col-aksi px-4 py-3">
                                        <div class="flex items-center justify-center gap-1">
                                            <a href="{{ route('transactions.show', $trx) }}" class="text-xs font-semibold text-teal-600 hover:text-teal-800 transition-colors px-2 py-1 rounded hover:bg-teal-50">Detail</a>
                                            @if($trx->status !== 'cancelled')
                                                <span class="text-gray-200">|</span>
                                                <button type="button"
                                                        @click="$dispatch('open-void-modal', { url: '{{ route('transactions.void', $trx) }}', number: '{{ addslashes($trx->transaction_number) }}' })"
                                                        class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors px-2 py-1 rounded hover:bg-red-50">Batalkan</button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Void Modal --}}
        <div x-show="showVoidModal" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/50 backdrop-blur-sm"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="showVoidModal = false"
                 class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4"
                 x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Konfirmasi Pembatalan</h3>
                <p class="text-sm text-gray-600 mb-1">Batalkan transaksi <span class="font-semibold text-red-600" x-text="trxNumber"></span>?</p>
                <p class="text-xs text-red-500 mb-6">Stok item akan dikembalikan otomatis.</p>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showVoidModal = false"
                            class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">Kembali</button>
                    <form :action="voidUrl" method="POST" class="inline-block">
                        @csrf
                        <button type="submit" class="px-4 py-2 text-sm font-semibold bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Ya, Batalkan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
