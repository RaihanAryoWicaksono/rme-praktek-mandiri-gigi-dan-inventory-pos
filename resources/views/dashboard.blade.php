<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ringkasan Klinik Hari Ini') }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        
        <!-- Top Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Pendapatan -->
            <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-teal-50 text-xs font-black uppercase tracking-widest mb-1 opacity-80">Pendapatan Hari Ini</p>
                    <h3 class="text-3xl font-black italic">Rp {{ number_format($revenueToday, 0, ',', '.') }}</h3>
                </div>
                <svg class="absolute right-0 bottom-0 w-32 h-32 text-white opacity-10 -mr-6 -mb-6 transform rotate-12" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>

            <!-- Total Transaksi -->
            <div class="bg-white rounded-2xl p-6 text-gray-800 border-2 border-teal-50 shadow-sm relative overflow-hidden">
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-gray-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Transaksi</p>
                        <h3 class="text-3xl font-black text-teal-600 italic">{{ $transactionsToday }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-teal-50 rounded-2xl flex items-center justify-center text-teal-600 border border-teal-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                </div>
            </div>
            
            <!-- Total Pengeluaran Bulan Ini -->
            <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
                <div class="relative z-10">
                    <p class="text-rose-100 text-sm font-semibold mb-1">Pengeluaran Bulan Ini</p>
                    <h3 class="text-3xl font-bold">Rp {{ number_format($expensesThisMonth, 0, ',', '.') }}</h3>
                </div>
                <svg class="absolute right-0 bottom-0 w-32 h-32 text-rose-400 opacity-30 -mr-6 -mb-6 transform rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
            </div>

            <!-- Stock Alerts -->
            <div class="bg-white rounded-2xl p-6 text-gray-800 border border-gray-100 shadow-sm relative overflow-hidden">
                 <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-semibold mb-1">Peringatan Stok Obat</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-3xl font-bold {{ $lowStockItems->count() > 0 ? 'text-red-500' : 'text-gray-900' }}">{{ $lowStockItems->count() }}</h3>
                            <span class="text-sm font-medium text-gray-500">item menipis</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 {{ $lowStockItems->count() > 0 || $nearExpiredBatches->count() > 0 || $expiredBatches->count() > 0 ? 'bg-red-50 text-red-500' : 'bg-green-50 text-green-500' }} rounded-full flex items-center justify-center text-red-500">
                        @if($lowStockItems->count() > 0 || $nearExpiredBatches->count() > 0 || $expiredBatches->count() > 0)
                            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        @else
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Area: Chart and Low Stock List -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Revenue Chart -->
            <div class="lg:col-span-2 bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900">Grafik Pendapatan (7 Hari Terakhir)</h3>
                </div>
                <div class="w-full h-80">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Low Stock List -->
            <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6 flex flex-col max-h-[25rem]">
                <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Stok Menipis</h3>
                    <a href="{{ route('stock.inbound') }}" class="text-sm text-indigo-600 font-semibold hover:text-indigo-800">Tambah Stok</a>
                </div>
                <div class="flex-1 overflow-y-auto pr-2 pb-4">
                    @if($lowStockItems->isEmpty() && $nearExpiredBatches->isEmpty() && $expiredBatches->isEmpty())
                        <div class="flex flex-col items-center justify-center h-full text-center text-gray-400">
                            <svg class="w-12 h-12 mb-2 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-sm">Silakan bersantai.<br>Semua stok aman!</p>
                        </div>
                    @else
                        <ul class="space-y-3">
                            @foreach($expiredBatches as $batch)
                                <li class="flex justify-between items-center bg-red-50 p-3 rounded-xl border border-red-200">
                                    <div>
                                        <p class="font-bold text-sm text-red-900">{{ $batch->item->name }}</p>
                                        <p class="text-xs text-red-600 font-bold">SUDAH EXPIRED: {{ \Carbon\Carbon::parse($batch->expiry_date)->format('d M Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold text-red-600 block">{{ $batch->stock }}</span>
                                        <span class="text-xs text-red-500">{{ $batch->item->unit_use }}</span>
                                    </div>
                                </li>
                            @endforeach

                            @foreach($nearExpiredBatches as $batch)
                                <li class="flex justify-between items-center bg-orange-50 p-3 rounded-xl border border-orange-200">
                                    <div>
                                        <p class="font-bold text-sm text-orange-900">{{ $batch->item->name }}</p>
                                        <p class="text-xs text-orange-600">Akan Expired: {{ \Carbon\Carbon::parse($batch->expiry_date)->format('d M Y') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold text-orange-600 block">{{ $batch->stock }}</span>
                                        <span class="text-xs text-orange-500">{{ $batch->item->unit_use }}</span>
                                    </div>
                                </li>
                            @endforeach

                            @foreach($lowStockItems as $item)
                                <li class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-200">
                                    <div>
                                        <p class="font-bold text-sm text-gray-900">{{ $item->name }}</p>
                                        <p class="text-xs text-gray-500">Sisa stok menipis (Min: {{ $item->min_stock_alert }})</p>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-bold text-red-500 block">{{ $item->batches_sum_stock ?? 0 }}</span>
                                        <span class="text-xs text-gray-500">{{ $item->unit_use }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
            
        </div>

        <!-- Recent Transactions -->
        <div class="bg-white border border-gray-100 shadow-sm rounded-2xl overflow-hidden mt-6">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900">5 Transaksi Terbaru</h3>
                <a href="{{ route('transactions.index') }}" class="text-sm text-indigo-600 font-semibold hover:text-indigo-800">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Transaksi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        @forelse($recentTransactions as $trx)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-indigo-600">{{ $trx->transaction_number }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $trx->created_at->diffForHumans() }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 uppercase font-mono">{{ $trx->patient->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-black text-gray-900">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <a href="{{ route('transactions.show', $trx) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-400 italic">Belum ada transaksi di sistem.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart Script Initialization -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            
            const labels = {!! json_encode($chartLabels) !!};
            const data = {!! json_encode($chartData) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: data,
                        borderColor: '#20B2AA', // teal-600
                        backgroundColor: 'rgba(32, 178, 170, 0.1)',
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#20B2AA',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value, index, values) {
                                    return 'Rp ' + new Intl.NumberFormat('id-ID', {notation: "compact", compactDisplay: "short"}).format(value);
                                }
                            },
                            grid: {
                                borderDash: [2, 4],
                                color: '#f3f4f6'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
