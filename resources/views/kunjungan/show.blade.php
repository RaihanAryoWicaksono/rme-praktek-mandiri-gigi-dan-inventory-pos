<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Kunjungan</h2>
    </x-slot>

    <div class="max-w-3xl space-y-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('kunjungan.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Detail Kunjungan</h1>
            <div class="ml-auto flex items-center gap-2">
                @if (!$kunjungan->rekamMedis)
                    <a href="{{ route('rekam-medis.create', ['kunjungan_id' => $kunjungan->id]) }}"
                       class="inline-flex items-center gap-1.5 bg-purple-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-purple-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Rekam Medis
                    </a>
                @else
                    <a href="{{ route('rekam-medis.show', $kunjungan->rekamMedis) }}"
                       class="inline-flex items-center gap-1.5 border border-purple-300 text-purple-700 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-purple-50 transition-colors">
                        Lihat Rekam Medis
                    </a>
                @endif
                <a href="{{ route('kunjungan.edit', $kunjungan) }}"
                   class="inline-flex items-center gap-1.5 border border-gray-300 text-gray-700 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                    Edit
                </a>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h2 class="font-semibold text-gray-900 text-lg">{{ $kunjungan->patient->display_name }}</h2>
                    <p class="text-sm font-mono text-teal-600">{{ $kunjungan->patient->no_rm }}</p>
                </div>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $kunjungan->badge_status }}">
                    {{ $kunjungan->label_status }}
                </span>
            </div>

            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                <div>
                    <dt class="text-gray-500 font-medium mb-1">Tanggal Kunjungan</dt>
                    <dd class="text-gray-900">{{ $kunjungan->tanggal_kunjungan->format('d F Y') }}</dd>
                </div>
                @if($kunjungan->tekanan_darah || $kunjungan->nadi)
                <div>
                    <dt class="text-gray-500 font-medium mb-1">Tanda Vital</dt>
                    <dd class="text-gray-900">
                        @if($kunjungan->tekanan_darah) TD: {{ $kunjungan->tekanan_darah }} mmHg @endif
                        @if($kunjungan->nadi) · Nadi: {{ $kunjungan->nadi }} /mnt @endif
                    </dd>
                </div>
                @endif
                <div class="sm:col-span-2">
                    <dt class="text-gray-500 font-medium mb-1">Keluhan Utama</dt>
                    <dd class="text-gray-900">{{ $kunjungan->keluhan_utama }}</dd>
                </div>
                @if($kunjungan->anamnesis)
                <div class="sm:col-span-2">
                    <dt class="text-gray-500 font-medium mb-1">Anamnesis</dt>
                    <dd class="text-gray-900 whitespace-pre-line">{{ $kunjungan->anamnesis }}</dd>
                </div>
                @endif
            </dl>
        </div>

        @if ($kunjungan->rekamMedis)
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="font-semibold text-gray-800 mb-4">Rekam Medis</h3>
                <dl class="space-y-3 text-sm">
                    <div>
                        <dt class="text-gray-500 font-medium mb-1">Diagnosis</dt>
                        <dd class="text-gray-900">{{ $kunjungan->rekamMedis->diagnosis }}</dd>
                    </div>
                    <div>
                        <dt class="text-gray-500 font-medium mb-1">Tindakan</dt>
                        <dd class="text-gray-900">{{ $kunjungan->rekamMedis->tindakan }}</dd>
                    </div>
                    @if($kunjungan->rekamMedis->resep)
                    <div>
                        <dt class="text-gray-500 font-medium mb-1">Resep</dt>
                        <dd class="text-gray-900 whitespace-pre-line">{{ $kunjungan->rekamMedis->resep }}</dd>
                    </div>
                    @endif
                </dl>
            </div>
        @endif
    </div>
</x-app-layout>
