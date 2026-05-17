<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Pengaturan Klinik</h2>
    </x-slot>

    <div class="max-w-3xl space-y-5">
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
    </div>
</x-app-layout>
