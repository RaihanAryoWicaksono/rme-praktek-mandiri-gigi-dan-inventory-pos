{{-- Picker Resep dari Master Item/Bahan (tipe obat) --}}
<div x-data="{
    open: false,
    search: '',
    options: {{ Js::from($obatItems->map(fn($i) => ['name' => $i->name, 'unit' => $i->unit_use])) }},
    picked: [],
    get filtered() {
        if (!this.search) return this.options;
        const q = this.search.toLowerCase();
        return this.options.filter(o => o.name.toLowerCase().includes(q));
    },
    pick(item) {
        if (this.picked.find(p => p.name === item.name)) return;
        this.picked.push(item);
        const ta = this.$refs.ta;
        ta.value = (ta.value.trim() ? ta.value.trim() + '\n' : '') + '- ' + item.name;
        this.open = false;
        this.search = '';
    },
    remove(name) {
        this.picked = this.picked.filter(p => p.name !== name);
        const ta = this.$refs.ta;
        const lines = ta.value.split('\n').filter(l => l.trim() !== '- ' + name);
        ta.value = lines.join('\n');
    }
}" @click.outside="open = false">

    <div class="flex items-center justify-between mb-1">
        <label class="block text-sm font-medium text-gray-700">Resep</label>
        <button type="button" @click="open = !open"
                class="inline-flex items-center gap-1 text-xs text-teal-600 hover:text-teal-700 font-medium">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Pilih Obat
        </button>
    </div>

    {{-- Chips terpilih --}}
    <div x-show="picked.length > 0" class="flex flex-wrap gap-1 mb-2">
        <template x-for="item in picked" :key="item.name">
            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-50 border border-blue-200 text-blue-700 rounded-full text-xs font-medium">
                <span x-text="item.name"></span>
                <button type="button" @click="remove(item.name)"
                        class="ml-0.5 text-blue-400 hover:text-red-500 leading-none">&times;</button>
            </span>
        </template>
    </div>

    {{-- Dropdown --}}
    <div x-show="open" x-cloak class="relative mb-2" style="z-index: 30;">
        <div class="absolute w-full bg-white border border-gray-200 rounded-lg shadow-lg">
            <div class="p-2 border-b border-gray-100">
                <input type="text" x-model="search" placeholder="Cari obat..."
                       class="w-full px-2 py-1.5 text-xs border border-gray-200 rounded-md focus:outline-none focus:ring-1 focus:ring-teal-400">
            </div>
            <ul class="max-h-44 overflow-y-auto py-1">
                <template x-for="item in filtered" :key="item.name">
                    <li @click="!picked.find(p => p.name === item.name) && pick(item)"
                        :class="picked.find(p => p.name === item.name)
                            ? 'opacity-40 cursor-default bg-gray-50'
                            : 'cursor-pointer hover:bg-blue-50'"
                        class="px-3 py-2 flex items-center justify-between">
                        <span class="text-sm text-gray-800" x-text="item.name"></span>
                        <span class="text-xs text-gray-400 ml-2 shrink-0" x-text="item.unit"></span>
                    </li>
                </template>
                <li x-show="filtered.length === 0" class="px-3 py-3 text-xs text-gray-400 text-center">
                    Tidak ada obat yang cocok
                </li>
            </ul>
        </div>
    </div>

    <textarea name="resep" rows="3" x-ref="ta"
              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 resize-none"
              placeholder="Resep obat yang diberikan...">{{ old('resep', $rekamMedis->resep ?? '') }}</textarea>
</div>
