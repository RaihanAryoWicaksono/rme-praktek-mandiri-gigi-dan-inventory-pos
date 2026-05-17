<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Data Pasien</h2>
    </x-slot>

    <div class="space-y-5">
        <div class="main-content-card p-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Data Pasien</h1>
                    <p class="text-sm text-gray-500 mt-1">Total {{ $patients->total() }} pasien terdaftar</p>
                </div>
                <a href="{{ route('patients.create') }}"
                   class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Pasien
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
            @endif

            <form method="GET" class="flex gap-2 mb-5">
                <div class="relative flex-1 max-w-sm">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama, No. RM, NIK, telepon..."
                           class="w-full pl-9 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                </div>
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition-colors">Cari</button>
                @if(request('search'))
                    <a href="{{ route('patients.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
                @endif
            </form>

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">No. RM</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Lengkap</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Jenis Kelamin</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Tgl Lahir</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden lg:table-cell">No. Telepon</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($patients as $patient)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 font-mono text-xs text-teal-700 font-medium">{{ $patient->no_rm ?? '-' }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="w-7 h-7 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 text-xs font-bold shrink-0">
                                                {{ strtoupper(substr($patient->display_name, 0, 1)) }}
                                            </div>
                                            <span class="font-medium text-gray-900">{{ $patient->display_name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 hidden sm:table-cell text-gray-600">
                                        {{ $patient->jenis_kelamin === 'L' ? 'Laki-laki' : ($patient->jenis_kelamin === 'P' ? 'Perempuan' : '-') }}
                                    </td>
                                    <td class="px-4 py-3 hidden md:table-cell text-gray-600">
                                        @if($patient->tanggal_lahir)
                                            {{ $patient->tanggal_lahir->format('d/m/Y') }}
                                            <span class="text-xs text-gray-400">({{ $patient->umur }} th)</span>
                                        @else -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 hidden lg:table-cell text-gray-600">{{ $patient->phone ?? '-' }}</td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('patients.show', $patient) }}"
                                               class="p-1.5 text-gray-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors" title="Detail">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            <a href="{{ route('patients.edit', $patient) }}"
                                               class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </a>
                                            <form method="POST" action="{{ route('patients.destroy', $patient) }}" onsubmit="return confirm('Hapus pasien ini?')">
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
                                    <td colspan="6" class="px-4 py-12 text-center text-sm text-gray-400">
                                        @if(request('search'))
                                            Tidak ada pasien yang cocok dengan "<strong>{{ request('search') }}</strong>".
                                        @else
                                            Belum ada data pasien. <a href="{{ route('patients.create') }}" class="text-teal-600 hover:underline">Tambah pasien baru</a>.
                                        @endif
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($patients->hasPages())
                    <div class="px-4 py-3 border-t border-gray-100">
                        {{ $patients->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
