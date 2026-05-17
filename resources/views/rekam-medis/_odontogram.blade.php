{{-- Odontogram SVG Interaktif — FDI Numbering System --}}
<div x-data="odontogram({{ $initialData ?? '{}' }})" class="space-y-4">

    <div class="flex flex-wrap gap-2 text-xs">
        @foreach ([
            ['normal','bg-white border border-gray-300','Normal'],
            ['karies','bg-red-400','Karies'],
            ['tambalan','bg-blue-400','Tambalan'],
            ['missing','bg-gray-900','Missing'],
            ['sisa_akar','bg-amber-700','Sisa Akar'],
            ['mahkota','bg-yellow-400','Mahkota'],
            ['implant','bg-green-500','Implant'],
            ['fraktur','bg-orange-500','Fraktur'],
        ] as [$code, $cls, $label])
            <div class="flex items-center gap-1.5">
                <span class="w-4 h-4 rounded-sm {{ $cls }} shrink-0"></span>
                <span class="text-gray-600">{{ $label }}</span>
            </div>
        @endforeach
    </div>

    <div x-show="popup.visible" x-cloak @click.self="popup.visible = false"
         class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/40">
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 p-4 w-72">
            <div class="flex items-center justify-between mb-3">
                <h4 class="font-semibold text-sm text-gray-800">Gigi <span x-text="popup.tooth"></span></h4>
                <button @click="popup.visible = false" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <label class="block text-xs font-medium text-gray-600 mb-1">Kondisi</label>
            <select x-model="popup.kondisi"
                    class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mb-3">
                <option value="normal">Normal</option>
                <option value="karies">Karies</option>
                <option value="tambalan">Tambalan</option>
                <option value="missing">Missing / Cabut</option>
                <option value="sisa_akar">Sisa Akar</option>
                <option value="mahkota">Mahkota / Crown</option>
                <option value="implant">Implant</option>
                <option value="fraktur">Fraktur</option>
            </select>

            <label class="block text-xs font-medium text-gray-600 mb-1">Permukaan</label>
            <div class="flex gap-1 mb-3">
                @foreach (['M','D','O','L','V'] as $s)
                    <button type="button" @click="toggleSurface('{{ $s }}')"
                            :class="popup.surfaces.includes('{{ $s }}') ? 'bg-teal-600 text-white border-teal-600' : 'bg-white text-gray-600 border-gray-300'"
                            class="flex-1 py-1 text-xs font-medium border rounded-md transition-colors">{{ $s }}</button>
                @endforeach
            </div>

            <label class="block text-xs font-medium text-gray-600 mb-1">Catatan</label>
            <input type="text" x-model="popup.catatan" placeholder="Opsional..."
                   class="w-full px-2 py-1.5 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500 mb-3">

            <div class="flex gap-2">
                <button type="button" @click="applyPopup"
                        class="flex-1 bg-teal-600 text-white py-1.5 rounded-lg text-sm font-medium hover:bg-teal-700 transition-colors">Simpan</button>
                <button type="button" @click="clearTooth(popup.tooth)"
                        class="px-3 bg-gray-100 text-gray-600 py-1.5 rounded-lg text-sm hover:bg-gray-200 transition-colors">Reset</button>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 p-4 overflow-x-auto">
        <div class="min-w-max">
            <div class="flex justify-center mb-1">
                <span class="text-xs text-gray-400 font-medium">Kanan ← RAHANG ATAS → Kiri</span>
            </div>
            <div class="flex justify-center gap-1 mb-1">
                @foreach ([[18,17,16,15,14,13,12,11],[21,22,23,24,25,26,27,28]] as $group)
                    <div class="flex gap-1">
                        @foreach ($group as $t)
                            <div class="flex flex-col items-center cursor-pointer" @click="openPopup({{ $t }})">
                                <span class="text-xs text-gray-400 mb-0.5">{{ $t }}</span>
                                <svg width="36" height="36" viewBox="0 0 36 36" class="rounded border" :class="getToothClass({{ $t }})">
                                    <polygon points="0,0 18,18 0,36" :fill="getSurfaceColor({{ $t }}, 'M')" stroke="white" stroke-width="0.5" />
                                    <polygon points="36,0 18,18 36,36" :fill="getSurfaceColor({{ $t }}, 'D')" stroke="white" stroke-width="0.5" />
                                    <polygon points="0,0 36,0 18,18" :fill="getSurfaceColor({{ $t }}, 'V')" stroke="white" stroke-width="0.5" />
                                    <polygon points="0,36 36,36 18,18" :fill="getSurfaceColor({{ $t }}, 'L')" stroke="white" stroke-width="0.5" />
                                    <circle cx="18" cy="18" r="7" :fill="getSurfaceColor({{ $t }}, 'O')" stroke="white" stroke-width="0.5" />
                                </svg>
                            </div>
                        @endforeach
                    </div>
                    @if(!$loop->last) <div class="w-px bg-gray-300 mx-1"></div> @endif
                @endforeach
            </div>
            <div class="flex justify-center my-2">
                <div class="h-px w-full bg-gray-200 max-w-lg"></div>
            </div>
            <div class="flex justify-center gap-1 mt-1">
                @foreach ([[48,47,46,45,44,43,42,41],[31,32,33,34,35,36,37,38]] as $group)
                    <div class="flex gap-1">
                        @foreach ($group as $t)
                            <div class="flex flex-col items-center cursor-pointer" @click="openPopup({{ $t }})">
                                <svg width="36" height="36" viewBox="0 0 36 36" class="rounded border" :class="getToothClass({{ $t }})">
                                    <polygon points="0,0 18,18 0,36" :fill="getSurfaceColor({{ $t }}, 'M')" stroke="white" stroke-width="0.5" />
                                    <polygon points="36,0 18,18 36,36" :fill="getSurfaceColor({{ $t }}, 'D')" stroke="white" stroke-width="0.5" />
                                    <polygon points="0,0 36,0 18,18" :fill="getSurfaceColor({{ $t }}, 'V')" stroke="white" stroke-width="0.5" />
                                    <polygon points="0,36 36,36 18,18" :fill="getSurfaceColor({{ $t }}, 'L')" stroke="white" stroke-width="0.5" />
                                    <circle cx="18" cy="18" r="7" :fill="getSurfaceColor({{ $t }}, 'O')" stroke="white" stroke-width="0.5" />
                                </svg>
                                <span class="text-xs text-gray-400 mt-0.5">{{ $t }}</span>
                            </div>
                        @endforeach
                    </div>
                    @if(!$loop->last) <div class="w-px bg-gray-300 mx-1"></div> @endif
                @endforeach
            </div>
            <div class="flex justify-center mt-1">
                <span class="text-xs text-gray-400 font-medium">Kanan ← RAHANG BAWAH → Kiri</span>
            </div>
        </div>
    </div>

    <input type="hidden" name="odontogram_data" :value="JSON.stringify(teeth)">
</div>

@push('scripts')
<script>
function odontogram(initialData) {
    const kondisiColors = {
        normal: null, karies: '#f87171', tambalan: '#60a5fa',
        missing: '#111827', sisa_akar: '#92400e', mahkota: '#fbbf24',
        implant: '#22c55e', fraktur: '#f97316',
    };

    return {
        teeth: (Array.isArray(initialData) ? {} : initialData) || {},
        popup: { visible: false, tooth: null, kondisi: 'normal', surfaces: [], catatan: '' },

        openPopup(num) {
            const t = String(num);
            const current = this.teeth[t] || { kondisi: 'normal', surfaces: [], catatan: '' };
            this.popup = { visible: true, tooth: t, kondisi: current.kondisi, surfaces: [...(current.surfaces || [])], catatan: current.catatan || '' };
        },

        toggleSurface(s) {
            const idx = this.popup.surfaces.indexOf(s);
            if (idx >= 0) this.popup.surfaces.splice(idx, 1);
            else this.popup.surfaces.push(s);
        },

        applyPopup() {
            const t = this.popup.tooth;
            if (this.popup.kondisi === 'normal' && this.popup.surfaces.length === 0 && !this.popup.catatan) {
                delete this.teeth[t];
            } else {
                this.teeth[t] = { kondisi: this.popup.kondisi, surfaces: [...this.popup.surfaces], catatan: this.popup.catatan };
            }
            this.teeth = { ...this.teeth };
            this.popup.visible = false;
        },

        clearTooth(t) {
            delete this.teeth[String(t)];
            this.teeth = { ...this.teeth };
            this.popup.visible = false;
        },

        getToothClass(num) {
            const t = this.teeth[String(num)];
            return (!t || t.kondisi === 'normal') ? 'border-gray-200' : 'border-gray-400';
        },

        getSurfaceColor(num, surface) {
            const t = this.teeth[String(num)];
            if (!t || t.kondisi === 'normal') return '#f9fafb';
            if (t.kondisi === 'missing') return kondisiColors.missing;
            const color = kondisiColors[t.kondisi] || '#f9fafb';
            if (t.surfaces && t.surfaces.length > 0) return t.surfaces.includes(surface) ? color : '#f9fafb';
            return color;
        },
    };
}
</script>
@endpush
