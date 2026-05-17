<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Persediaan & Stok</h2>
    </x-slot>

    <div class="space-y-5">

        {{-- Ringkasan Persediaan --}}
        <div class="main-content-card overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900">Ringkasan Persediaan Semua Item</h3>
                    <p class="text-xs text-gray-500 mt-0.5">{{ count($items) }} item terdaftar</p>
                </div>
                @php $lowCount = $items->filter(fn($i) => ($i->batches_sum_stock ?? 0) < $i->min_stock_alert)->count(); @endphp
                @if($lowCount > 0)
                    <span class="text-xs font-bold px-3 py-1 bg-red-100 text-red-700 rounded-full">{{ $lowCount }} item perlu order</span>
                @else
                    <span class="text-xs font-bold px-3 py-1 bg-green-100 text-green-700 rounded-full">Semua stok aman</span>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Item</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tipe</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Sisa Stok</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Satuan</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($items as $item)
                            @php $stok = $item->batches_sum_stock ?? 0; $low = $stok < $item->min_stock_alert; @endphp
                            <tr class="hover:bg-gray-50 {{ $low ? 'bg-red-50/40' : '' }}">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $item->name }}</td>
                                <td class="px-4 py-3 text-gray-500 capitalize">{{ $item->type }}</td>
                                <td class="px-4 py-3 text-right font-bold {{ $low ? 'text-red-600' : 'text-teal-700' }}">{{ $stok }}</td>
                                <td class="px-4 py-3 text-gray-400">{{ $item->unit_use }}</td>
                                <td class="px-4 py-3">
                                    @if($low)
                                        <span class="inline-flex text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700">Perlu Order!</span>
                                    @else
                                        <span class="inline-flex text-xs font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Aman</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Item Kedaluwarsa --}}
        <div class="bg-white rounded-xl border border-red-200 overflow-hidden">
            <div class="px-6 py-4 bg-red-50 border-b border-red-200 flex items-center gap-2">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="font-bold text-red-800 text-sm">Daftar Item Sudah / Hampir Kedaluwarsa</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-red-50/50 border-b border-red-100">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-red-700 uppercase tracking-wide">Nama Item</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-red-700 uppercase tracking-wide">No. Batch</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-red-700 uppercase tracking-wide">Tgl Expired</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-red-700 uppercase tracking-wide">Qty Tersisa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-red-50">
                        @forelse($expiredBatches as $batch)
                            @php $isExpired = \Carbon\Carbon::parse($batch->expiry_date)->isPast(); @endphp
                            <tr class="hover:bg-red-50/30">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $batch->item->name }}</td>
                                <td class="px-4 py-3 text-gray-500 font-mono text-xs">{{ $batch->batch_number ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-bold {{ $isExpired ? 'text-red-700' : 'text-amber-600' }}">
                                        {{ \Carbon\Carbon::parse($batch->expiry_date)->format('d/m/Y') }}
                                    </span>
                                    @if($isExpired)
                                        <span class="ml-1 text-xs text-red-500 italic">Expired</span>
                                    @else
                                        <span class="ml-1 text-xs text-amber-500 italic">{{ \Carbon\Carbon::parse($batch->expiry_date)->diffForHumans() }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right font-bold text-gray-800">{{ $batch->stock }} {{ $batch->item->unit_use }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-12 text-center text-sm text-gray-400 italic">
                                    Tidak ada stok yang kedaluwarsa. Semua baik-baik saja!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>
