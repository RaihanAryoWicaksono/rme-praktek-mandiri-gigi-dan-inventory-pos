<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Kunjungan</h2>
    </x-slot>

    <div class="max-w-3xl space-y-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('kunjungan.show', $kunjungan) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Kunjungan</h1>
        </div>

        <form method="POST" action="{{ route('kunjungan.update', $kunjungan) }}" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Data Kunjungan --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Data Kunjungan</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pasien <span class="text-red-500">*</span></label>
                    <select name="patient_id" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @foreach ($patients as $p)
                            <option value="{{ $p->id }}" @selected(old('patient_id', $kunjungan->patient_id) == $p->id)>
                                {{ $p->nama_lengkap }} — {{ $p->no_rm }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_kunjungan"
                               value="{{ old('tanggal_kunjungan', $kunjungan->tanggal_kunjungan->format('Y-m-d')) }}" required
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="antrian"          @selected(old('status', $kunjungan->status) === 'antrian')>Antrian</option>
                            <option value="sedang_diperiksa" @selected(old('status', $kunjungan->status) === 'sedang_diperiksa')>Sedang Diperiksa</option>
                            <option value="selesai"          @selected(old('status', $kunjungan->status) === 'selesai')>Selesai</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Anamnesis & Vital Sign --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Anamnesis & Vital Sign</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keluhan Utama <span class="text-red-500">*</span></label>
                    <textarea name="keluhan_utama" rows="2" required
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('keluhan_utama', $kunjungan->keluhan_utama) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Anamnesis</label>
                    <textarea name="anamnesis" rows="3"
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('anamnesis', $kunjungan->anamnesis) }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tekanan Darah</label>
                        <div class="relative">
                            <input type="text" name="tekanan_darah"
                                   value="{{ old('tekanan_darah', $kunjungan->tekanan_darah) }}"
                                   placeholder="120/80" maxlength="10"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 pr-12">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">mmHg</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nadi</label>
                        <div class="relative">
                            <input type="text" name="nadi"
                                   value="{{ old('nadi', $kunjungan->nadi) }}"
                                   placeholder="80" maxlength="5"
                                   class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 pr-14">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400">x/mnt</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pb-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-teal-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-teal-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('kunjungan.show', $kunjungan) }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
