<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Rekam Medis</h2>
    </x-slot>

    <div class="space-y-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('rekam-medis.show', $rekamMedis) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Rekam Medis</h1>
        </div>

        <div class="bg-teal-50 border border-teal-200 rounded-xl px-5 py-3 text-sm text-teal-800">
            <strong>{{ $rekamMedis->kunjungan->patient->display_name }}</strong>
            <span class="mx-1.5 text-teal-400">·</span>
            {{ $rekamMedis->kunjungan->patient->no_rm }}
            <span class="mx-1.5 text-teal-400">·</span>
            Kunjungan {{ $rekamMedis->kunjungan->tanggal_kunjungan->format('d M Y') }}
        </div>

        <form method="POST" action="{{ route('rekam-medis.update', $rekamMedis) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

                {{-- Left Column: Data Klinis --}}
                <div class="lg:col-span-5 space-y-4">
                    <div class="bg-white rounded-xl border border-gray-200 p-5 space-y-4">
                        <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Data Klinis</h2>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosis <span class="text-red-500">*</span></label>
                            <textarea name="diagnosis" rows="2" required
                                      class="w-full px-3 py-2 text-sm border @error('diagnosis') border-red-400 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('diagnosis', $rekamMedis->diagnosis) }}</textarea>
                            @error('diagnosis') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            @include('rekam-medis._picker_tindakan')
                        </div>

                        <div>
                            @include('rekam-medis._picker_resep')
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                            <textarea name="catatan" rows="3"
                                      class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('catatan', $rekamMedis->catatan) }}</textarea>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex items-center gap-3">
                        <button type="submit"
                                class="inline-flex items-center gap-2 bg-teal-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-teal-700 transition-colors shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('rekam-medis.show', $rekamMedis) }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
                    </div>
                </div>

                {{-- Right Column: Odontogram --}}
                <div class="lg:col-span-7 h-fit sticky top-4">
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Odontogram Digital</h2>
                        <p class="text-xs text-gray-400 mb-4">Klik gigi untuk menandai kondisi. Data sebelumnya sudah dimuat.</p>
                        @include('rekam-medis._odontogram', ['initialData' => json_encode($rekamMedis->odontogram_data ?: new \stdClass())])
                    </div>
                </div>

            </div>
        </form>
    </div>
</x-app-layout>
