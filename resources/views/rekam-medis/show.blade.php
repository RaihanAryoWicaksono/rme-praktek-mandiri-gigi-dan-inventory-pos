<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Rekam Medis</h2>
    </x-slot>

    <div class="space-y-4">

        {{-- Header Bar --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('rekam-medis.index') }}" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Rekam Medis</h1>
            <div class="ml-auto">
                <a href="{{ route('rekam-medis.edit', $rekamMedis) }}"
                   class="inline-flex items-center gap-1.5 bg-teal-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-teal-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit
                </a>
            </div>
        </div>

        {{-- Patient Info Bar --}}
        <div class="bg-white rounded-xl border border-gray-200 p-4">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-full bg-teal-100 flex items-center justify-center text-teal-700 font-bold text-lg shrink-0">
                    {{ strtoupper(substr($rekamMedis->patient->display_name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <h2 class="font-bold text-gray-900">{{ $rekamMedis->patient->display_name }}</h2>
                    <p class="text-sm text-gray-500">
                        <span class="font-mono text-teal-600">{{ $rekamMedis->patient->no_rm }}</span>
                        <span class="mx-1.5 text-gray-300">·</span>
                        {{ $rekamMedis->kunjungan->tanggal_kunjungan->format('d F Y') }}
                        <span class="mx-1.5 text-gray-300">·</span>
                        Kunjungan #{{ $rekamMedis->kunjungan->id }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Main Two-Column Layout --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

            {{-- Left: Data Klinis + Foto --}}
            <div class="lg:col-span-5 space-y-4">

                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Data Klinis</h3>
                    </div>
                    <dl class="divide-y divide-gray-100 text-sm">
                        <div class="px-5 py-4">
                            <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Diagnosis</dt>
                            <dd class="text-gray-900 whitespace-pre-line">{{ $rekamMedis->diagnosis }}</dd>
                        </div>
                        <div class="px-5 py-4">
                            <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Tindakan</dt>
                            <dd class="text-gray-900 whitespace-pre-line">{{ $rekamMedis->tindakan }}</dd>
                        </div>
                        @if($rekamMedis->resep)
                        <div class="px-5 py-4">
                            <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Resep</dt>
                            <dd class="text-gray-900 whitespace-pre-line bg-gray-50 px-3 py-2 rounded-lg border border-gray-100">{{ $rekamMedis->resep }}</dd>
                        </div>
                        @endif
                        @if($rekamMedis->catatan)
                        <div class="px-5 py-4">
                            <dt class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Catatan</dt>
                            <dd class="text-gray-900 whitespace-pre-line">{{ $rekamMedis->catatan }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                @if($rekamMedis->foto)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Foto Klinis</h3>
                    </div>
                    <div class="p-4">
                        <a href="{{ asset('storage/' . $rekamMedis->foto) }}" target="_blank" class="block">
                            <img src="{{ asset('storage/' . $rekamMedis->foto) }}"
                                 class="w-full rounded-lg border border-gray-100 object-cover hover:opacity-90 transition-opacity cursor-zoom-in">
                        </a>
                        <p class="text-xs text-gray-400 mt-2 text-center">Klik untuk lihat ukuran penuh</p>
                    </div>
                </div>
                @endif

            </div>

            {{-- Right: Odontogram --}}
            <div class="lg:col-span-7">
                @if($rekamMedis->odontogram_data)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden"
                     x-data="odontogram({{ json_encode($rekamMedis->odontogram_data ?: new \stdClass()) }})">
                    <div class="px-5 py-3 bg-gray-50 border-b border-gray-100">
                        <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Odontogram</h3>
                    </div>
                    <div class="p-5">
                        <div class="flex flex-wrap gap-3 text-xs mb-5">
                            @foreach ([['karies','bg-red-400','Karies'],['tambalan','bg-blue-400','Tambalan'],['missing','bg-gray-900','Missing'],['sisa_akar','bg-amber-700','Sisa Akar'],['mahkota','bg-yellow-400','Mahkota'],['implant','bg-green-500','Implant'],['fraktur','bg-orange-500','Fraktur']] as [$code, $cls, $label])
                                <div class="flex items-center gap-1.5">
                                    <span class="w-3 h-3 rounded-sm {{ $cls }} shrink-0"></span>
                                    <span class="text-gray-600">{{ $label }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="overflow-x-auto">
                            <div class="min-w-max">
                                <div class="flex justify-center mb-1"><span class="text-xs text-gray-400">Kanan ← RAHANG ATAS → Kiri</span></div>
                                <div class="flex justify-center gap-1 mb-1">
                                    @foreach ([[18,17,16,15,14,13,12,11],[21,22,23,24,25,26,27,28]] as $group)
                                        <div class="flex gap-1">
                                            @foreach ($group as $t)
                                                <div class="flex flex-col items-center">
                                                    <span class="text-xs text-gray-400 mb-0.5">{{ $t }}</span>
                                                    <svg width="36" height="36" viewBox="0 0 36 36" class="rounded border border-gray-200">
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
                                <div class="flex justify-center my-2"><div class="h-px w-full bg-gray-200 max-w-lg"></div></div>
                                <div class="flex justify-center gap-1 mt-1">
                                    @foreach ([[48,47,46,45,44,43,42,41],[31,32,33,34,35,36,37,38]] as $group)
                                        <div class="flex gap-1">
                                            @foreach ($group as $t)
                                                <div class="flex flex-col items-center">
                                                    <svg width="36" height="36" viewBox="0 0 36 36" class="rounded border border-gray-200">
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
                                <div class="flex justify-center mt-1"><span class="text-xs text-gray-400">Kanan ← RAHANG BAWAH → Kiri</span></div>
                            </div>
                        </div>

                        @php $odData = $rekamMedis->odontogram_data; @endphp
                        @if(!empty($odData))
                        @php $gigiAbnormal = array_filter($odData, fn($i) => isset($i['kondisi']) && $i['kondisi'] !== 'normal'); @endphp
                        @if(!empty($gigiAbnormal))
                        <div class="mt-5 border-t border-gray-100 pt-4">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Keterangan Gigi</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 text-xs">
                                @foreach($gigiAbnormal as $gigi => $info)
                                <div class="bg-gray-50 rounded-lg px-3 py-2 border border-gray-100">
                                    <span class="font-bold text-gray-800">Gigi {{ $gigi }}</span>
                                    <p class="text-gray-600 capitalize mt-0.5">{{ str_replace('_', ' ', $info['kondisi']) }}</p>
                                    @if(!empty($info['surfaces'])) <p class="text-gray-400 text-[10px]">Permukaan: {{ implode(', ', $info['surfaces']) }}</p> @endif
                                    @if(!empty($info['catatan'])) <p class="text-gray-500 italic text-[10px] mt-0.5">{{ $info['catatan'] }}</p> @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
                @else
                <div class="bg-white rounded-xl border border-gray-200 p-8 flex items-center justify-center">
                    <p class="text-sm text-gray-400 italic">Tidak ada data odontogram.</p>
                </div>
                @endif
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
    function odontogram(initialData) {
        const kondisiColors = {
            normal:'#f9fafb', karies:'#f87171', tambalan:'#60a5fa',
            missing:'#111827', sisa_akar:'#92400e', mahkota:'#fbbf24',
            implant:'#22c55e', fraktur:'#f97316',
        };
        return {
            teeth: initialData || {},
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
</x-app-layout>
