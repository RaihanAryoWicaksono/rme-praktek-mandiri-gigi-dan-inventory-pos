<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kunjungan</h2>
    </x-slot>

    <div class="space-y-5">
        <div class="main-content-card p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Kunjungan</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ $kunjungans->total() }} kunjungan ditemukan</p>
                </div>
                <a href="{{ route('kunjungan.create') }}"
                   class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Kunjungan
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            <form method="GET" class="flex flex-wrap gap-2 mb-5">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari pasien..."
                           class="pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <input type="date" name="tanggal" value="{{ request('tanggal', today()->format('Y-m-d')) }}"
                       class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                <select name="status" class="px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Status</option>
                    <option value="antrian" @selected(request('status') === 'antrian')>Antrian</option>
                    <option value="sedang_diperiksa" @selected(request('status') === 'sedang_diperiksa')>Sedang Diperiksa</option>
                    <option value="selesai" @selected(request('status') === 'selesai')>Selesai</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition-colors">Filter</button>
            </form>

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Pasien</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Tanggal</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Keluhan</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                                <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($kunjungans as $k)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3">
                                        <p class="font-medium text-gray-900">{{ $k->patient->display_name }}</p>
                                        <p class="text-xs text-teal-600 font-mono">{{ $k->patient->no_rm }}</p>
                                    </td>
                                    <td class="px-4 py-3 hidden sm:table-cell text-gray-600">
                                        {{ $k->tanggal_kunjungan->format('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3 hidden md:table-cell text-gray-600 max-w-xs truncate">
                                        {{ $k->keluhan_utama }}
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $k->badge_status }}">
                                            {{ $k->label_status }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center justify-center gap-1">
                                            <a href="{{ route('kunjungan.show', $k) }}"
                                               class="text-xs font-semibold text-teal-600 hover:text-teal-800 transition-colors px-2 py-1 rounded hover:bg-teal-50">Detail</a>
                                            <span class="text-gray-200">|</span>
                                            <a href="{{ route('kunjungan.edit', $k) }}"
                                               class="text-xs font-semibold text-blue-600 hover:text-blue-800 transition-colors px-2 py-1 rounded hover:bg-blue-50">Edit</a>
                                            <span class="text-gray-200">|</span>
                                            <form method="POST" action="{{ route('kunjungan.destroy', $k) }}" onsubmit="return confirm('Hapus kunjungan ini beserta rekam medisnya?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-xs font-semibold text-red-500 hover:text-red-700 transition-colors px-2 py-1 rounded hover:bg-red-50">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center text-sm text-gray-400">
                                        Tidak ada kunjungan pada filter yang dipilih.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($kunjungans->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100">
                        {{ $kunjungans->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
