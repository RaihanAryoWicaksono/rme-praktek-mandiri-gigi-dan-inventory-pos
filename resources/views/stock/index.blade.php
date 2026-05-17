<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Stok & Mutasi</h2>
    </x-slot>

    <div class="space-y-5">
        <div class="main-content-card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Riwayat Pergerakan Stok</h1>
                    <p class="text-sm text-gray-500 mt-1">Semua mutasi masuk, keluar, dan koreksi</p>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('stock.correction') }}"
                       class="inline-flex items-center gap-2 bg-amber-500 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-amber-600 transition-colors">
                        Koreksi Manual
                    </a>
                    <a href="{{ route('stock.inbound') }}"
                       class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Stok Masuk
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Item</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Batch</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tipe</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Qty</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden lg:table-cell">Alasan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($mutations as $mutation)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 text-gray-600 whitespace-nowrap">{{ $mutation->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $mutation->item->name }}</td>
                                    <td class="px-4 py-3 hidden md:table-cell text-gray-500">
                                        {{ $mutation->batch->batch_number ?? '-' }}
                                        @if($mutation->batch && $mutation->batch->expiry_date)
                                            <p class="text-xs text-red-500">Exp: {{ \Carbon\Carbon::parse($mutation->batch->expiry_date)->format('d/m/Y') }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($mutation->type == 'in')
                                            <span class="inline-flex text-xs font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Masuk</span>
                                        @elseif($mutation->type == 'out')
                                            <span class="inline-flex text-xs font-bold px-2 py-0.5 rounded-full bg-red-100 text-red-700">Keluar</span>
                                        @else
                                            <span class="inline-flex text-xs font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">Koreksi</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 font-bold {{ $mutation->quantity > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $mutation->quantity > 0 ? '+' : '' }}{{ $mutation->quantity }} {{ $mutation->item->unit_use }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-500 hidden lg:table-cell">{{ $mutation->reason }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center text-sm text-gray-400 italic">Belum ada riwayat mutasi stok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($mutations->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100">
                        {{ $mutations->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
