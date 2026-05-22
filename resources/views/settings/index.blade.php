<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengaturan Klinik</h2>
    </x-slot>

    <div class="max-w-3xl space-y-5" x-data="{ popupOpen: false, selected: null }">
        <h1 class="text-2xl font-bold text-gray-900">Pengaturan Klinik</h1>

        @if(session('success'))
            <div class="p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST" class="space-y-4">
            @csrf

            {{-- Profil Klinik --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Profil Klinik</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Klinik <span class="text-red-500">*</span></label>
                    <input type="text" name="clinic_name" value="{{ $settings['clinic_name'] ?? '' }}" required
                           class="w-full px-3 py-2 text-sm border @error('clinic_name') border-red-400 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('clinic_name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="tel" name="clinic_phone" value="{{ $settings['clinic_phone'] ?? '' }}"
                               inputmode="numeric" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @error('clinic_phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dokter</label>
                        <input type="text" name="doctor_name" value="{{ $settings['doctor_name'] ?? '' }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
                    <textarea name="clinic_address" rows="2" required
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ $settings['clinic_address'] ?? '' }}</textarea>
                    @error('clinic_address') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Footer Struk / Nota</label>
                    <textarea name="receipt_footer" rows="2"
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none">{{ $settings['receipt_footer'] ?? '' }}</textarea>
                </div>
            </div>

            {{-- Konfigurasi Notifikasi --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Konfigurasi Notifikasi</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Peringatan Expired (H- hari) <span class="text-red-500">*</span></label>
                        <input type="number" name="expiry_alert_days" value="{{ $settings['expiry_alert_days'] ?? '30' }}" min="1" required
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <p class="mt-1 text-xs text-gray-400">Item disorot jika mendekati expired dalam rentang hari ini.</p>
                        @error('expiry_alert_days') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Batas Minimum Stok Default <span class="text-red-500">*</span></label>
                        <input type="number" name="min_stock_alert" value="{{ $settings['min_stock_alert'] ?? '10' }}" min="0" required
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <p class="mt-1 text-xs text-gray-400">Stok diperingatkan menipis jika kurang dari angka ini.</p>
                        @error('min_stock_alert') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 pb-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-teal-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-teal-700 transition-colors shadow-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Simpan Pengaturan
                </button>
            </div>
        </form>

        {{-- Antrian Kunjungan Hari Ini --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Antrian Kunjungan Hari Ini</h2>
                    <p class="text-sm text-gray-400 mt-0.5">{{ \Carbon\Carbon::today()->translatedFormat('l, d F Y') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-teal-700 bg-teal-50 border border-teal-100 px-3 py-1 rounded-full">
                        <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
                        {{ $kunjunganHariIni->count() }} Pasien
                    </span>
                    <a href="{{ route('kunjungan.create') }}"
                       class="inline-flex items-center gap-1.5 bg-teal-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-teal-700 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Tambah
                    </a>
                </div>
            </div>

            @if($kunjunganHariIni->isEmpty())
                <div class="py-12 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-sm">Belum ada kunjungan hari ini.</p>
                </div>
            @else
                <div class="divide-y divide-gray-50">
                    @foreach($kunjunganHariIni as $index => $k)
                        <div class="flex items-center gap-4 px-6 py-3.5 hover:bg-gray-50/60 transition-colors group">
                            {{-- Nomor antrian --}}
                            <div class="w-8 h-8 rounded-full bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-700 text-sm font-bold shrink-0">
                                {{ $index + 1 }}
                            </div>

                            {{-- Avatar pasien --}}
                            <div class="w-9 h-9 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 text-sm font-bold shrink-0 overflow-hidden">
                                @if($k->patient->fotos && count($k->patient->fotos) > 0)
                                    <img src="{{ asset('storage/' . $k->patient->fotos[0]) }}" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr($k->patient->display_name, 0, 1)) }}
                                @endif
                            </div>

                            {{-- Info pasien --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ $k->patient->display_name }}</p>
                                <p class="text-xs text-gray-400 font-mono">{{ $k->patient->no_rm }} &middot; {{ Str::limit($k->keluhan_utama, 40) }}</p>
                            </div>

                            {{-- Status badge --}}
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 {{ $k->badge_status }}">
                                {{ $k->label_status }}
                            </span>

                            {{-- Aksi --}}
                            <div class="flex items-center gap-1.5 shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button
                                    @click="selected = {{ $k->toJson() }}; selected.patient_name = '{{ addslashes($k->patient->display_name) }}'; selected.no_rm = '{{ $k->patient->no_rm }}'; selected.has_rm = {{ $k->rekamMedis ? 'true' : 'false' }}; selected.rm_id = {{ $k->rekamMedis?->id ?? 'null' }}; popupOpen = true"
                                    class="text-xs text-teal-600 hover:text-teal-800 font-medium px-2 py-1 rounded hover:bg-teal-50 transition">
                                    Detail
                                </button>
                                <a href="{{ route('kunjungan.show', $k) }}"
                                   class="text-xs text-gray-500 hover:text-gray-700 font-medium px-2 py-1 rounded hover:bg-gray-100 transition">
                                    Buka
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Statistik ringkas --}}
                @php
                    $antrian = $kunjunganHariIni->where('status', 'antrian')->count();
                    $diperiksa = $kunjunganHariIni->where('status', 'sedang_diperiksa')->count();
                    $selesai = $kunjunganHariIni->where('status', 'selesai')->count();
                @endphp
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-100 flex items-center gap-5 text-xs text-gray-500">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                        Antrian: <strong class="text-gray-700">{{ $antrian }}</strong>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                        Diperiksa: <strong class="text-gray-700">{{ $diperiksa }}</strong>
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-green-400"></span>
                        Selesai: <strong class="text-gray-700">{{ $selesai }}</strong>
                    </span>
                    <a href="{{ route('kunjungan.index') }}" class="ml-auto text-teal-600 hover:underline font-medium">Lihat semua →</a>
                </div>
            @endif
        </div>

        {{-- Popup Detail Kunjungan --}}
        <div x-show="popupOpen" x-cloak
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0">

            {{-- Backdrop --}}
            <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" @click="popupOpen = false"></div>

            {{-- Modal --}}
            <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md z-10"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95">

                <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                    <h3 class="font-bold text-gray-900">Detail Kunjungan</h3>
                    <button @click="popupOpen = false" class="text-gray-400 hover:text-gray-600 transition p-1 rounded-lg hover:bg-gray-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-5 space-y-4" x-show="selected">
                    {{-- Pasien --}}
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100">
                        <div class="w-12 h-12 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 text-lg font-bold shrink-0">
                            <span x-text="selected?.patient_name?.charAt(0)?.toUpperCase()"></span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900" x-text="selected?.patient_name"></p>
                            <p class="text-xs font-mono text-teal-600" x-text="selected?.no_rm"></p>
                        </div>
                    </div>

                    {{-- Info kunjungan --}}
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500">Status</dt>
                            <dd>
                                <span x-text="selected?.label_status"
                                      :class="{
                                        'bg-yellow-100 text-yellow-800': selected?.status === 'antrian',
                                        'bg-blue-100 text-blue-800': selected?.status === 'sedang_diperiksa',
                                        'bg-green-100 text-green-800': selected?.status === 'selesai'
                                      }"
                                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold">
                                </span>
                            </dd>
                        </div>
                        <div class="flex justify-between gap-4">
                            <dt class="text-gray-500 shrink-0">Keluhan</dt>
                            <dd class="text-gray-900 text-right" x-text="selected?.keluhan_utama || '-'"></dd>
                        </div>
                        <div class="flex justify-between gap-4" x-show="selected?.anamnesis">
                            <dt class="text-gray-500 shrink-0">Anamnesis</dt>
                            <dd class="text-gray-900 text-right" x-text="selected?.anamnesis"></dd>
                        </div>
                        <div class="flex justify-between" x-show="selected?.tekanan_darah">
                            <dt class="text-gray-500">Tekanan Darah</dt>
                            <dd class="text-gray-900 font-mono" x-text="(selected?.tekanan_darah || '') + ' mmHg'"></dd>
                        </div>
                        <div class="flex justify-between" x-show="selected?.nadi">
                            <dt class="text-gray-500">Nadi</dt>
                            <dd class="text-gray-900 font-mono" x-text="(selected?.nadi || '') + ' /mnt'"></dd>
                        </div>
                    </dl>

                    {{-- Rekam medis status --}}
                    <div class="rounded-xl p-3 text-sm flex items-center gap-2"
                         :class="selected?.has_rm ? 'bg-green-50 text-green-700 border border-green-100' : 'bg-yellow-50 text-yellow-700 border border-yellow-100'">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  :d="selected?.has_rm ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'" />
                        </svg>
                        <span x-text="selected?.has_rm ? 'Rekam medis sudah dibuat' : 'Rekam medis belum dibuat'"></span>
                    </div>
                </div>

                {{-- Aksi --}}
                <div class="px-6 py-4 border-t border-gray-100 flex items-center gap-2 justify-end">
                    <button @click="popupOpen = false"
                            class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded-lg transition">
                        Tutup
                    </button>
                    <a :href="'/kunjungan/' + selected?.id"
                       class="inline-flex items-center gap-1.5 bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-teal-700 transition">
                        Buka Kunjungan
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
