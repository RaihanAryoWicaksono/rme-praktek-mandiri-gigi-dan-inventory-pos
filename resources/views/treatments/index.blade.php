<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Master Tindakan</h2>
    </x-slot>

    <div class="space-y-5" x-data="{
        showDeleteModal: false, deleteUrl: '', itemName: '',
        openModal(url, name) { this.deleteUrl = url; this.itemName = name; this.showDeleteModal = true; }
    }" @open-delete-modal.window="openModal($event.detail.url, $event.detail.name)">

        <div class="main-content-card p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Katalog Tindakan</h1>
                    <p class="text-sm text-gray-500 mt-1">{{ count($treatments) }} tindakan terdaftar</p>
                </div>
                <a href="{{ route('treatments.create') }}"
                   class="inline-flex items-center gap-2 bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Tindakan
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm">{{ session('error') }}</div>
            @endif

            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm datatable" data-empty-message="Belum ada data tindakan.">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Tindakan</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kategori</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tarif Dasar</th>
                                <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                                <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($treatments as $treatment)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-gray-900">{{ $treatment->name }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $treatment->category ?? '-' }}</td>
                                    <td class="px-4 py-3 font-semibold text-gray-800">Rp {{ number_format($treatment->base_price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        @if($treatment->is_active)
                                            <span class="inline-flex text-xs font-semibold px-2 py-0.5 rounded-full bg-green-100 text-green-700">Aktif</span>
                                        @else
                                            <span class="inline-flex text-xs font-semibold px-2 py-0.5 rounded-full bg-red-100 text-red-700">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('treatments.bom.index', $treatment) }}" class="text-xs font-medium text-teal-600 hover:text-teal-800">Kelola BOM</a>
                                            <a href="{{ route('treatments.edit', $treatment) }}" class="text-xs font-medium text-blue-600 hover:text-blue-800">Edit</a>
                                            <button type="button"
                                                    @click="$dispatch('open-delete-modal', { url: '{{ route('treatments.destroy', $treatment) }}', name: '{{ addslashes($treatment->name) }}' })"
                                                    class="text-xs font-medium text-red-500 hover:text-red-700">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
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
                <p class="text-sm text-gray-600 mb-6">Hapus tindakan <span class="font-semibold text-red-600" x-text="itemName"></span>?</p>
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
