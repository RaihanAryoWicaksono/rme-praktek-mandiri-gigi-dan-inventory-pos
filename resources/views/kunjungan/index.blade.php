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
                                <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
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
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('kunjungan.show', $k) }}"
                                               class="p-1.5 text-gray-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors" title="Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('kunjungan.edit', $k) }}"
                                               class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('kunjungan.destroy', $k) }}" onsubmit="return confirm('Hapus kunjungan ini beserta rekam medisnya?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
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
