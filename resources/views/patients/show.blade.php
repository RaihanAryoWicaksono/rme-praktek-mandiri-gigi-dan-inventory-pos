<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Pasien</h2>
    </x-slot>

    <div class="space-y-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('patients.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Detail Pasien</h1>
            <div class="ml-auto flex items-center gap-2">
                <a href="{{ route('kunjungan.create', ['patient_id' => $patient->id]) }}"
                   class="inline-flex items-center gap-1.5 bg-blue-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Kunjungan
                </a>
                <a href="{{ route('patients.edit', $patient) }}"
                   class="inline-flex items-center gap-1.5 border border-gray-300 text-gray-700 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            <div class="lg:col-span-1 space-y-4">
                <div class="bg-white rounded-xl border border-gray-200 p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 text-lg font-bold overflow-hidden shrink-0">
                            @if($patient->fotos && count($patient->fotos) > 0)
                                <img src="{{ asset('storage/' . $patient->fotos[0]) }}" alt="{{ $patient->display_name }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($patient->display_name, 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <h2 class="font-semibold text-gray-900">{{ $patient->display_name }}</h2>
                            <p class="text-xs font-mono text-teal-600">{{ $patient->no_rm ?? 'No RM' }}</p>
                        </div>
                    </div>

                    <dl class="space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">NIK</dt>
                            <dd class="text-gray-900 font-medium">{{ $patient->nik ?? '-' }}</dd>
                        </div>
                        @if($patient->tanggal_lahir)
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Tgl Lahir</dt>
                            <dd class="text-gray-900">{{ $patient->tanggal_lahir->format('d M Y') }} ({{ $patient->umur }} th)</dd>
                        </div>
                        @endif
                        @if($patient->jenis_kelamin)
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Jenis Kelamin</dt>
                            <dd class="text-gray-900">{{ $patient->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                        </div>
                        @endif
                        @if($patient->golongan_darah)
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Gol. Darah</dt>
                            <dd class="text-gray-900 font-medium">{{ $patient->golongan_darah === '-' ? 'Tidak diketahui' : $patient->golongan_darah }}</dd>
                        </div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Telepon</dt>
                            <dd class="text-gray-900">{{ $patient->phone ?? '-' }}</dd>
                        </div>
                        @if($patient->email)
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Email</dt>
                            <dd class="text-gray-900 text-xs">{{ $patient->email }}</dd>
                        </div>
                        @endif
                        @if($patient->pekerjaan)
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Pekerjaan</dt>
                            <dd class="text-gray-900">{{ $patient->pekerjaan }}</dd>
                        </div>
                        @endif
                    </dl>

                    @if($patient->alamat)
                    <div class="mt-3 pt-3 border-t border-gray-100 text-sm">
                        <p class="text-gray-500 mb-1">Alamat</p>
                        <p class="text-gray-900">{{ $patient->alamat }}</p>
                    </div>
                    @endif

                    @if($patient->alergi)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-xs font-medium text-red-600 mb-1">⚠ Alergi</p>
                        <p class="text-sm text-red-700 bg-red-50 px-3 py-2 rounded-lg">{{ $patient->alergi }}</p>
                    </div>
                    @endif

                    @if($patient->fotos && count($patient->fotos) > 0)
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <p class="text-xs font-medium text-gray-500 mb-2">Foto Pasien</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($patient->fotos as $path)
                            <a href="{{ asset('storage/' . $path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $path) }}" class="w-16 h-16 object-cover rounded-lg border border-gray-200 hover:opacity-80 transition-opacity">
                            </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl border border-gray-200">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h2 class="font-semibold text-gray-800">Riwayat Kunjungan</h2>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @forelse ($kunjungans as $k)
                            <div class="px-5 py-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-sm font-medium text-gray-900">
                                                {{ $k->tanggal_kunjungan->format('d M Y') }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $k->badge_status }}">
                                                {{ $k->label_status }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600">{{ $k->keluhan_utama }}</p>
                                        @if($k->tekanan_darah || $k->nadi)
                                            <p class="text-xs text-gray-400 mt-1">
                                                @if($k->tekanan_darah) TD: {{ $k->tekanan_darah }} mmHg @endif
                                                @if($k->nadi) · Nadi: {{ $k->nadi }} /mnt @endif
                                            </p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-1 shrink-0">
                                        <a href="{{ route('kunjungan.show', $k) }}" class="text-xs text-teal-600 hover:underline">Detail</a>
                                        @if(!$k->rekamMedis)
                                            <span class="text-gray-300 mx-1">|</span>
                                            <a href="{{ route('rekam-medis.create', ['kunjungan_id' => $k->id]) }}"
                                               class="text-xs text-blue-600 hover:underline">Buat RM</a>
                                        @else
                                            <span class="text-gray-300 mx-1">|</span>
                                            <a href="{{ route('rekam-medis.show', $k->rekamMedis) }}"
                                               class="text-xs text-purple-600 hover:underline">Lihat RM</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-5 py-10 text-center text-sm text-gray-400">
                                Belum ada riwayat kunjungan.
                            </div>
                        @endforelse
                    </div>
                    @if ($kunjungans->hasPages())
                        <div class="px-5 py-3 border-t border-gray-100">
                            {{ $kunjungans->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
