<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Pasien</h2>
    </x-slot>

    <div class="max-w-4xl space-y-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('patients.show', $patient) }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Pasien</h1>
        </div>

        <form method="POST" action="{{ route('patients.update', $patient) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @method('PUT')

            {{-- Identitas Pasien --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Identitas Pasien</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Rekam Medis</label>
                        <input type="text" value="{{ $patient->no_rm }}" readonly
                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 text-gray-500 font-mono tracking-wider">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $patient->nama_lengkap) }}" required
                               class="w-full px-3 py-2 text-sm border @error('nama_lengkap') border-red-400 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @error('nama_lengkap') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
                        <input type="text" name="nik" value="{{ old('nik', $patient->nik) }}" maxlength="16" placeholder="16 digit"
                               class="w-full px-3 py-2 text-sm border @error('nik') border-red-400 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @error('nik') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir"
                               value="{{ old('tanggal_lahir', $patient->tanggal_lahir?->format('Y-m-d')) }}" required
                               max="{{ now()->subDay()->format('Y-m-d') }}"
                               class="w-full px-3 py-2 text-sm border @error('tanggal_lahir') border-red-400 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @error('tanggal_lahir') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option value="L" @selected(old('jenis_kelamin', $patient->jenis_kelamin) === 'L')>Laki-laki</option>
                            <option value="P" @selected(old('jenis_kelamin', $patient->jenis_kelamin) === 'P')>Perempuan</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Golongan Darah <span class="text-red-500">*</span></label>
                        <select name="golongan_darah" required
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            @foreach (['A','B','AB','O','-'] as $gol)
                                <option value="{{ $gol }}" @selected(old('golongan_darah', $patient->golongan_darah) === $gol)>
                                    {{ $gol === '-' ? 'Tidak Diketahui' : $gol }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pekerjaan</label>
                        <input type="text" name="pekerjaan" value="{{ old('pekerjaan', $patient->pekerjaan) }}" maxlength="50"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>
            </div>

            {{-- Kontak & Alamat --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Kontak & Alamat</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon <span class="text-red-500">*</span></label>
                        <input type="tel" name="phone" value="{{ old('phone', $patient->phone) }}" required maxlength="15"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $patient->email) }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
                    <textarea name="alamat" rows="2" required
                              class="w-full px-3 py-2 text-sm border @error('alamat') border-red-400 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('alamat', $patient->alamat) }}</textarea>
                    @error('alamat') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Medis & Foto --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Medis & Foto</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alergi (obat / bahan)</label>
                        <textarea name="alergi" rows="4"
                                  class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ old('alergi', $patient->alergi) }}</textarea>
                        <p class="mt-1.5 text-xs text-gray-400">Kosongkan jika tidak ada alergi yang diketahui.</p>
                    </div>
                    <div x-data="{
                        previews: [],
                        accumulated: new DataTransfer(),
                        addFiles(event) {
                            Array.from(event.target.files).forEach(file => {
                                this.accumulated.items.add(file);
                                this.previews.push(URL.createObjectURL(file));
                            });
                            this.$refs.fotoInput.files = this.accumulated.files;
                        },
                        remove(index) {
                            this.previews.splice(index, 1);
                            const dt = new DataTransfer();
                            Array.from(this.accumulated.files).filter((_, i) => i !== index).forEach(f => dt.items.add(f));
                            this.accumulated = dt;
                            this.$refs.fotoInput.files = dt.files;
                        }
                    }">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tambah Foto Baru</label>
                        <div class="flex flex-wrap gap-2 mb-2" x-show="previews.length > 0">
                            <template x-for="(src, i) in previews" :key="i">
                                <div class="relative w-16 h-16">
                                    <img :src="src" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                    <button type="button" @click="remove(i)"
                                            class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full text-xs flex items-center justify-center hover:bg-red-600">&times;</button>
                                </div>
                            </template>
                        </div>
                        <label class="flex items-center gap-2 w-fit cursor-pointer border border-dashed border-gray-300 rounded-lg px-4 py-2.5 hover:border-teal-400 hover:bg-teal-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span class="text-sm text-gray-500">Pilih foto...</span>
                            <input type="file" name="fotos[]" multiple accept="image/jpg,image/jpeg,image/png"
                                   x-ref="fotoInput" @change="addFiles($event)" class="hidden">
                        </label>
                        <p class="mt-1.5 text-xs text-gray-400">Foto baru ditambahkan ke yang sudah ada. JPG / PNG, maks. 2 MB.</p>
                    </div>
                </div>

                @if($patient->fotos && count($patient->fotos) > 0)
                <div class="mt-5 pt-5 border-t border-gray-100">
                    <p class="text-xs font-semibold text-gray-500 mb-3">Foto Tersimpan</p>
                    <div class="flex flex-wrap gap-3">
                        @foreach($patient->fotos as $i => $path)
                        <div class="relative w-16 h-16 group">
                            <img src="{{ asset('storage/' . $path) }}" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                            <form method="POST" action="{{ route('patients.foto.destroy', [$patient, $i]) }}"
                                  onsubmit="return confirm('Hapus foto ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-red-500 text-white rounded-full text-xs flex items-center justify-center hover:bg-red-600 opacity-0 group-hover:opacity-100 transition-opacity">
                                    &times;
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="flex items-center gap-3 pb-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-teal-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-teal-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Perubahan
                </button>
                <a href="{{ route('patients.show', $patient) }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
