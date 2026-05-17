<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Pendapatan</h2>
    </x-slot>

    <div class="space-y-5">

        {{-- Filter & Export --}}
        <div class="main-content-card p-5">
            <form action="{{ route('reports.revenue') }}" method="GET" class="flex flex-wrap items-end gap-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                           class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                           class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <button type="submit" name="export" value=""
                        class="px-5 py-2 bg-teal-600 text-white text-sm font-semibold rounded-lg hover:bg-teal-700 transition-colors">
                    Filter
                </button>
                <div class="ml-auto flex items-center gap-2">
                    <button type="submit" formtarget="_blank" name="export" value="pdf"
                            class="px-4 py-2 bg-gray-700 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">
                        Cetak PDF
                    </button>
                    <button type="submit" name="export" value="excel"
                            class="px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-lg hover:bg-emerald-700 transition-colors">
                        Export Excel
                    </button>
                </div>
            </form>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-teal-600 p-6 rounded-xl text-white">
                <p class="text-xs font-bold uppercase tracking-widest opacity-70">Total Pendapatan</p>
                <h3 class="text-3xl font-black mt-2">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-rose-500 p-6 rounded-xl text-white">
                <p class="text-xs font-bold uppercase tracking-widest opacity-70">Total Pengeluaran</p>
                <h3 class="text-3xl font-black mt-2">Rp {{ number_format($totalExpense, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-emerald-500 p-6 rounded-xl text-white">
                <p class="text-xs font-bold uppercase tracking-widest opacity-70">Laba Bersih</p>
                <h3 class="text-3xl font-black mt-2">Rp {{ number_format($netProfit, 0, ',', '.') }}</h3>
            </div>
        </div>

        {{-- Data Tables --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- Pendapatan --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col" style="max-height: 34rem;">
                <div class="px-5 py-3.5 border-b border-gray-200 bg-gray-50 shrink-0">
                    <h4 class="font-semibold text-gray-800 text-sm">Data Pendapatan (Transaksi)</h4>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200 sticky top-0">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Pasien</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Bayar</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($transactions as $trx)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $trx->date->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-900">{{ $trx->patient->name }}</p>
                                        <p class="text-xs text-teal-600 font-mono">{{ $trx->transaction_number }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php $pm = strtolower($trx->payment_method ?? ''); @endphp
                                        @if($pm === 'tunai' || $pm === 'cash')
                                            <span class="inline-flex text-xs font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-700">TUNAI</span>
                                        @elseif($pm === 'qris')
                                            <span class="inline-flex text-xs font-bold px-2 py-0.5 rounded-full bg-green-100 text-green-700">QRIS</span>
                                        @elseif($pm === 'transfer_bank' || $pm === 'transfer')
                                            <span class="inline-flex text-xs font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-700">TRANSFER</span>
                                        @else
                                            <span class="inline-flex text-xs font-bold px-2 py-0.5 rounded-full bg-gray-100 text-gray-600">{{ strtoupper($pm ?: '-') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-gray-900 whitespace-nowrap">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-10 text-center text-sm text-gray-400 italic">Tidak ada transaksi pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-2.5 border-t border-gray-100 bg-gray-50 shrink-0 text-right">
                    <span class="text-xs text-gray-500">{{ count($transactions) }} transaksi</span>
                    <span class="ml-4 text-sm font-bold text-teal-700">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
            </div>

            {{-- Pengeluaran --}}
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden flex flex-col" style="max-height: 34rem;">
                <div class="px-5 py-3.5 border-b border-gray-200 bg-gray-50 shrink-0">
                    <h4 class="font-semibold text-gray-800 text-sm">Data Pengeluaran</h4>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200 sticky top-0">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Keterangan</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nominal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($expenses as $exp)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ \Carbon\Carbon::parse($exp->date)->format('d/m/Y') }}</td>
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-900">{{ $exp->name }}</p>
                                        @if($exp->description)
                                            <p class="text-xs text-gray-400 mt-0.5">{{ $exp->description }}</p>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right font-bold text-rose-600 whitespace-nowrap">Rp {{ number_format($exp->amount, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-10 text-center text-sm text-gray-400 italic">Tidak ada pengeluaran pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-5 py-2.5 border-t border-gray-100 bg-gray-50 shrink-0 text-right">
                    <span class="text-xs text-gray-500">{{ count($expenses) }} transaksi</span>
                    <span class="ml-4 text-sm font-bold text-rose-600">Rp {{ number_format($totalExpense, 0, ',', '.') }}</span>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
