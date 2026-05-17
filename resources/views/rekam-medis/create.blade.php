<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Rekam Medis</h2>
    </x-slot>

    <div class="max-w-4xl space-y-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('rekam-medis.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Buat Rekam Medis</h1>
        </div>

        <form method="POST" action="{{ route('rekam-medis.store') }}" enctype="multipart/form-data" class="space-y-4">
            @csrf

            {{-- Pilih Kunjungan --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Pilih Kunjungan</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kunjungan <span class="text-red-500">*</span></label>
                    <select name="kunjungan_id" required
                            class="w-full px-3 py-2 text-sm border @error('kunjungan_id') border-red-400 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Pilih kunjungan pasien...</option>
                        @foreach ($kunjungans as $k)
                            <option value="{{ $k->id }}" @selected(old('kunjungan_id', $kunjungan?->id) == $k->id)>
                                {{ $k->patient->display_name }} — {{ $k->tanggal_kunjungan->format('d M Y') }} · {{ $k->keluhan_utama }}
                            </option>
                        @endforeach
                    </select>
                    @error('kunjungan_id') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Klinis --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Data Klinis</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosis <span class="text-red-500">*</span></label>
                    <textarea name="diagnosis" rows="2" required placeholder="Diagnosis klinis..."
                              class="w-full px-3 py-2 text-sm border @error('diagnosis') border-red-400 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('diagnosis') }}</textarea>
                    @error('diagnosis') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tindakan <span class="text-red-500">*</span></label>
                    <textarea name="tindakan" rows="3" required placeholder="Tindakan yang dilakukan..."
                              class="w-full px-3 py-2 text-sm border @error('tindakan') border-red-400 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('tindakan') }}</textarea>
                    @error('tindakan') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Resep</label>
                        <textarea name="resep" rows="3" placeholder="Resep obat yang diberikan..."
                                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('resep') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Tambahan</label>
                        <textarea name="catatan" rows="3"
                                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('catatan') }}</textarea>
                    </div>
                </div>

                {{-- Foto Klinis --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Klinis</label>
                    <div x-data="{ preview: null }" class="flex items-start gap-4">
                        <div x-show="preview" class="shrink-0">
                            <img :src="preview" class="w-24 h-24 object-cover rounded-lg border border-gray-200 shadow-sm">
                        </div>
                        <div>
                            <label class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-teal-700 bg-teal-50 border border-teal-300 rounded-lg hover:bg-teal-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Pilih Foto
                                <input type="file" name="foto" accept="image/*" class="hidden"
                                       @change="preview = $event.target.files[0] ? URL.createObjectURL($event.target.files[0]) : null">
                            </label>
                            <p class="mt-1.5 text-xs text-gray-400">Format: JPG, PNG, WebP. Maks 2MB.</p>
                            @error('foto') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Odontogram --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-1">Odontogram Digital</h2>
                <p class="text-xs text-gray-400 mb-4">Klik gigi untuk menandai kondisi dan permukaan yang terdampak.</p>
                @include('rekam-medis._odontogram', ['initialData' => '{}'])
            </div>

            <div class="flex items-center gap-3 pb-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-teal-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-teal-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Rekam Medis
                </button>
                <a href="{{ route('rekam-medis.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
            </div>
        </form>
    </div>

    @push('scripts')
    <script>
    </script>
    @endpush
</x-app-layout>
