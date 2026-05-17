<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola BOM</h2>
    </x-slot>

    <div class="space-y-5" x-data="{
        showDeleteModal: false, deleteUrl: '', itemName: '',
        openModal(url, name) { this.deleteUrl = url; this.itemName = name; this.showDeleteModal = true; }
    }" @open-delete-modal.window="openModal($event.detail.url, $event.detail.name)">

        <div class="flex items-center gap-3">
            <a href="{{ route('treatments.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Kelola BOM</h1>
                <p class="text-sm text-gray-500 mt-0.5">{{ $treatment->name }}</p>
            </div>
        </div>

        @if(session('success'))
            <div class="p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            {{-- Buat Template Baru --}}
            <div class="bg-white rounded-xl border border-gray-200 p-6 h-fit">
                <h2 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">Buat Template Baru</h2>
                <form action="{{ route('treatments.bom.store', $treatment) }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Template <span class="text-red-500">*</span></label>
                        <input type="text" name="name" placeholder="Contoh: Tambal Kecil" required
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                    </div>
                    <button type="submit"
                            class="w-full py-2 bg-teal-600 text-white text-sm font-semibold rounded-lg hover:bg-teal-700 transition-colors">
                        Buat Template
                    </button>
                </form>
            </div>

            {{-- Daftar Template --}}
            <div class="md:col-span-2 space-y-4">
                @forelse($treatment->bomTemplates as $template)
                    <div class="bg-white rounded-xl border border-gray-200 p-5">
                        <div class="flex items-start justify-between mb-4 pb-3 border-b border-gray-100">
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $template->name }}</h3>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $template->items->count() }} bahan</p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('bom.edit', $template) }}"
                                   class="px-3 py-1.5 text-xs font-medium bg-teal-50 text-teal-700 rounded-lg hover:bg-teal-100 transition-colors">
                                    Atur Bahan
                                </a>
                                <button type="button"
                                        @click="$dispatch('open-delete-modal', { url: '{{ route('bom.destroy', $template) }}', name: '{{ addslashes($template->name) }}' })"
                                        class="p-1.5 text-gray-300 hover:text-red-500 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <ul class="space-y-1.5">
                            @forelse($template->items as $bomItem)
                                <li class="flex items-center justify-between text-sm px-3 py-2 bg-gray-50 rounded-lg">
                                    <span class="text-gray-700">{{ $bomItem->item->name }}</span>
                                    <span class="font-semibold text-gray-800">{{ $bomItem->quantity }} {{ $bomItem->unit }}</span>
                                </li>
                            @empty
                                <li class="text-sm text-gray-400 italic px-1">Belum ada bahan. Klik "Atur Bahan" untuk menambahkan.</li>
                            @endforelse
                        </ul>
                    </div>
                @empty
                    <div class="bg-white rounded-xl border border-gray-200 p-12 text-center">
                        <p class="text-gray-400 italic text-sm">Belum ada template BOM. Buat template baru di sebelah kiri.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Delete Modal --}}
        <div x-show="showDeleteModal" x-cloak
             class="fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/50 backdrop-blur-sm"
             x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
            <div @click.away="showDeleteModal = false"
                 class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4"
                 x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
                <h3 class="text-lg font-bold text-gray-900 mb-2">Konfirmasi Hapus</h3>
                <p class="text-sm text-gray-600 mb-6">Hapus template <span class="font-semibold text-red-600" x-text="itemName"></span>?</p>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="showDeleteModal = false"
                            class="px-4 py-2 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition">Batal</button>
                    <form :action="deleteUrl" method="POST" class="inline-block">
                        @csrf @method('DELETE')
                        <button type="submit" class="px-4 py-2 text-sm font-semibold bg-red-600 text-white rounded-lg hover:bg-red-700 transition">Ya, Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
