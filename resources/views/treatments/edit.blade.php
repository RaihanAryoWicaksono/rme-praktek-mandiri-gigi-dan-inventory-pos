<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Tindakan</h2>
    </x-slot>

    <div class="max-w-2xl space-y-5">
        <div class="flex items-center gap-3">
            <a href="{{ route('treatments.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Edit Tindakan</h1>
        </div>

        <form action="{{ route('treatments.update', $treatment) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Detail Tindakan</h2>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Tindakan <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $treatment->name) }}" required autofocus
                           class="w-full px-3 py-2 text-sm border @error('name') border-red-400 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                        <input type="text" name="category" value="{{ old('category', $treatment->category) }}"
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @error('category') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tarif Dasar (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="base_price" value="{{ old('base_price', $treatment->base_price) }}" min="0" step="1000" required
                               class="w-full px-3 py-2 text-sm border @error('base_price') border-red-400 @else border-gray-300 @enderror rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        @error('base_price') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="inline-flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $treatment->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-teal-600 shadow-sm focus:ring-teal-500">
                        <span class="text-sm text-gray-700">Aktif (tampil di POS)</span>
                    </label>
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
                <a href="{{ route('treatments.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Batal</a>
            </div>
        </form>
    </div>
</x-app-layout>
