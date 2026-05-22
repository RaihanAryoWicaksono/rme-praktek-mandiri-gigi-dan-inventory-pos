@extends('layouts.public')

@section('title', 'drg. Moh Ariv Widodo Dental Clinic')

@push('styles')
<style>
    @@keyframes slideInRight {
        from { transform: translateX(110%); opacity: 0; }
        to   { transform: translateX(0);   opacity: 1; }
    }
    @@keyframes slideOutRight {
        from { transform: translateX(0);   opacity: 1; }
        to   { transform: translateX(110%); opacity: 0; }
    }
    .notif-enter { animation: slideInRight 0.4s cubic-bezier(0.34,1.56,0.64,1) forwards; }
    .notif-exit  { animation: slideOutRight 0.3s ease-in forwards; }
</style>
@endpush

@section('content')
<section id="home" class="relative overflow-hidden bg-gradient-to-r from-white via-white to-teal-50 pt-10">
    <div class="absolute right-0 top-0 hidden h-full w-1/2 bg-gradient-to-br from-teal-300 via-teal-400 to-teal-500 lg:block"></div>
    <div class="relative mx-auto max-w-7xl px-6 py-16 lg:px-12 lg:py-24">
        <div class="grid items-center gap-12 lg:grid-cols-2">
            <div class="max-w-2xl">
                <span class="inline-flex rounded-full bg-teal-50 px-4 py-2 text-sm font-semibold text-teal-600">drg. Moh Ariv Widodo Dental Clinic</span>
                <h1 class="mt-6 text-4xl font-bold leading-tight text-slate-900 sm:text-5xl lg:text-6xl">Check Your Dental Health Today</h1>
                <p class="mt-6 max-w-xl text-base leading-8 text-gray-500 sm:text-lg">Layanan kesehatan gigi profesional dengan fasilitas modern, dokter berpengalaman, dan suasana klinik yang nyaman untuk membantu Anda tersenyum lebih sehat setiap hari.</p>
                <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center">
                    <a href="#appointment" class="inline-flex items-center justify-center rounded-full bg-teal-500 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-600 hover:shadow-lg">Get Started</a>
                    <a href="#appointment" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 transition hover:text-teal-500">An appointment
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            </div>

            <div class="relative">
                <div class="absolute -left-4 top-8 rounded-2xl bg-white p-4 shadow-lg sm:-left-10">
                    <p class="text-sm font-semibold text-slate-900">No 1</p>
                    <p class="text-xs text-gray-500">Top Best Clinics</p>
                </div>
                <div class="absolute right-4 top-10 rounded-2xl bg-white p-3 shadow-md">
                    <div class="flex -space-x-2">
                        <img src="https://placehold.co/40x40" alt="Patient" class="h-10 w-10 rounded-full border-2 border-white object-cover">
                        <img src="https://placehold.co/40x40" alt="Patient" class="h-10 w-10 rounded-full border-2 border-white object-cover">
                        <img src="https://placehold.co/40x40" alt="Patient" class="h-10 w-10 rounded-full border-2 border-white object-cover">
                    </div>
                </div>
                <div class="relative mx-auto max-w-md rounded-[2rem] bg-white/20 p-4 backdrop-blur-sm lg:ml-auto">
                    <img src="https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&w=900&q=80" alt="Dokter gigi profesional" class="h-full w-full rounded-[2rem] object-cover shadow-2xl">
                </div>
            </div>
        </div>
    </div>
</section>

<section id="services" class="bg-white py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-12">
        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr] lg:items-end">
            <div>
                <p class="text-sm font-semibold text-teal-500">Clinic Services</p>
                <h2 class="mt-3 max-w-xl text-3xl font-bold leading-tight text-slate-900 sm:text-4xl">The Best Quality Service You Can Get</h2>
            </div>
            <p class="max-w-lg text-base leading-8 text-gray-500">Kami menyediakan rangkaian layanan perawatan gigi yang lengkap untuk menjaga kesehatan, fungsi, dan estetika senyum Anda dengan pendekatan yang aman dan personal.</p>
        </div>

        <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @include('components.card', ['title' => 'Denture Care', 'description' => 'Perawatan gigi tiruan yang membantu menjaga kenyamanan, fungsi kunyah, dan kesehatan rongga mulut Anda.', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 11c0-2.761 2.239-5 5-5s5 2.239 5 5v2a5 5 0 11-10 0v-2z" /></svg>'])
            @include('components.card', ['title' => 'Dental Implant', 'description' => 'Solusi pengganti gigi yang hilang dengan hasil yang kuat, stabil, dan terlihat natural.', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3v8m0 0l3-3m-3 3L9 8M7 14h10l-1 5H8l-1-5z" /></svg>'])
            @include('components.card', ['title' => 'General Dentistry', 'description' => 'Layanan pemeriksaan, konsultasi, dan tindakan dasar untuk menjaga kesehatan gigi sehari-hari.', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 4c3.866 0 7 1.79 7 4s-1 10-4 10c-1.333 0-1.667-2-3-2s-1.667 2-3 2c-3 0-4-6-4-10s3.134-4 7-4z" /></svg>'])
            @include('components.card', ['title' => 'Cosmetic Braces', 'description' => 'Perawatan ortodonti modern untuk merapikan susunan gigi dan meningkatkan kepercayaan diri.', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 9h14M5 15h14M8 9v6m4-6v6m4-6v6" /></svg>'])
            @include('components.card', ['title' => 'Extractions', 'description' => 'Tindakan pencabutan gigi yang dilakukan dengan prosedur aman dan perhatian pada kenyamanan pasien.', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 4l4 4m-1 3l3 3m-8 6l5-5m-7-7l4 4" /></svg>'])
            @include('components.card', ['title' => 'Restorative Dentistry', 'description' => 'Perbaikan struktur gigi melalui restorasi untuk mengembalikan fungsi, bentuk, dan penampilan.', 'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3l7 4v5c0 5-3.5 7.5-7 9-3.5-1.5-7-4-7-9V7l7-4z" /></svg>'])
        </div>
    </div>
</section>

<section id="about" class="bg-slate-50 py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-12">
        <div class="grid items-center gap-12 lg:grid-cols-2">
            <div class="relative mx-auto max-w-lg">
                <img src="https://images.unsplash.com/photo-1606811841689-23dfddce3e95?auto=format&fit=crop&w=900&q=80" alt="Dokter klinik gigi" class="w-full rounded-[2rem] object-cover shadow-lg">
                <div class="absolute bottom-6 left-6 rounded-2xl bg-white px-5 py-4 shadow-lg">
                    <p class="text-3xl font-bold text-slate-900">100%</p>
                    <p class="text-sm text-gray-500">Maintain Dental</p>
                </div>
            </div>
            <div>
                <p class="text-sm font-semibold text-teal-500">Take Care Teeth</p>
                <h2 class="mt-3 text-3xl font-bold leading-tight text-slate-900 sm:text-4xl">Let's Take Care Of Dental Health</h2>
                <p class="mt-6 max-w-xl text-base leading-8 text-gray-500">Kesehatan gigi yang baik dimulai dari perawatan rutin dan pemeriksaan berkala. Kami membantu Anda menjaga gigi tetap kuat, bersih, dan sehat dengan layanan yang menyeluruh.</p>
                <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center">
                    <a href="#appointment" class="inline-flex items-center justify-center rounded-full bg-teal-500 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-600">Let's Starting</a>
                    <a href="#facility" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 transition hover:text-teal-500">Read More
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="facility" class="bg-white py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-12">
        <div class="max-w-3xl">
            <p class="text-sm font-semibold text-teal-500">Clinic Features</p>
            <h2 class="mt-3 text-3xl font-bold leading-tight text-slate-900 sm:text-4xl">Enjoy The Features In Our Clinic</h2>
        </div>
        <div class="mt-12 grid gap-6 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-lg"><h3 class="text-xl font-bold text-slate-900">Online System</h3><p class="mt-3 text-sm leading-7 text-gray-500">Sistem reservasi online yang memudahkan Anda mengatur jadwal perawatan kapan saja.</p><a href="#" class="mt-4 inline-block text-sm font-semibold text-teal-500">More about this</a></div>
            <div class="rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-lg"><h3 class="text-xl font-bold text-slate-900">24 Hours Open</h3><p class="mt-3 text-sm leading-7 text-gray-500">Siap membantu kebutuhan pasien dengan jam layanan yang fleksibel dan responsif.</p><a href="#" class="mt-4 inline-block text-sm font-semibold text-teal-500">More about this</a></div>
            <div class="rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-lg"><h3 class="text-xl font-bold text-slate-900">Easy Access</h3><p class="mt-3 text-sm leading-7 text-gray-500">Lokasi mudah dijangkau dengan akses yang nyaman untuk pasien dan keluarga.</p><a href="#" class="mt-4 inline-block text-sm font-semibold text-teal-500">More about this</a></div>
            <div class="rounded-2xl border border-slate-100 p-6 shadow-sm hover:shadow-lg"><h3 class="text-xl font-bold text-slate-900">More Facilities</h3><p class="mt-3 text-sm leading-7 text-gray-500">Didukung fasilitas modern untuk pemeriksaan, tindakan, dan konsultasi yang lebih optimal.</p><a href="#" class="mt-4 inline-block text-sm font-semibold text-teal-500">More about this</a></div>
        </div>
    </div>
</section>

<section class="bg-slate-50 py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-12">
        <div class="grid items-center gap-12 lg:grid-cols-2">
            <div class="grid gap-5 sm:grid-cols-2">
                <img src="https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?auto=format&fit=crop&w=900&q=80" alt="Preview video praktik 1" class="h-72 w-full rounded-2xl object-cover shadow-sm">
                <img src="https://images.unsplash.com/photo-1598256989800-fe5f95da9787?auto=format&fit=crop&w=900&q=80" alt="Preview video praktik 2" class="h-72 w-full rounded-2xl object-cover shadow-sm sm:translate-y-8">
            </div>
            <div>
                <p class="text-sm font-semibold text-teal-500">Practice Videos</p>
                <h2 class="mt-3 text-3xl font-bold leading-tight text-slate-900 sm:text-4xl">See The Video Of Our Practice Process</h2>
                <p class="mt-6 max-w-xl text-base leading-8 text-gray-500">Kami mendokumentasikan proses pelayanan secara profesional agar pasien dapat melihat bagaimana kami bekerja dengan teliti, higienis, dan berorientasi pada kenyamanan.</p>
                <div class="mt-8 flex flex-col gap-4 sm:flex-row sm:items-center">
                    <a href="#" class="inline-flex items-center justify-center rounded-full bg-teal-500 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-teal-600">Our Youtube</a>
                    <a href="#" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-700 transition hover:text-teal-500">See more video
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="appointment" class="bg-white py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-12">
        <div class="grid items-start gap-10 rounded-[2rem] bg-gradient-to-r from-slate-50 to-teal-50 px-6 py-10 lg:grid-cols-2 lg:px-12">

            {{-- Kiri: CTA --}}
            <div class="flex flex-col justify-center">
                <p class="text-sm font-semibold text-teal-500">Antrian Hari Ini</p>
                <h2 class="mt-3 max-w-xl text-3xl font-bold leading-tight text-slate-900 sm:text-4xl">Cek Nomor Antrian Anda</h2>
                <p class="mt-6 max-w-xl text-base leading-8 text-gray-500">Pantau posisi antrian Anda secara langsung. Daftar di bawah diperbarui secara real-time sesuai jadwal kunjungan hari ini.</p>
                <div class="mt-6 flex items-center gap-3">
                    <span class="inline-flex items-center gap-2 rounded-full bg-teal-100 px-4 py-2 text-sm font-semibold text-teal-700">
                        <span class="h-2 w-2 rounded-full bg-teal-500 animate-pulse"></span>
                        <span id="antrian-badge-count">{{ $kunjunganHariIni->count() }} Pasien Terdaftar Hari Ini</span>
                    </span>
                </div>
                <div class="mt-6 grid grid-cols-3 gap-4 text-center">
                    <div class="rounded-2xl bg-yellow-50 border border-yellow-100 px-4 py-4">
                        <p id="count-menunggu" class="text-2xl font-bold text-yellow-600">{{ $kunjunganHariIni->where('status', 'antrian')->count() }}</p>
                        <p class="mt-1 text-xs text-yellow-700 font-medium">Menunggu</p>
                    </div>
                    <div class="rounded-2xl bg-blue-50 border border-blue-100 px-4 py-4">
                        <p id="count-diperiksa" class="text-2xl font-bold text-blue-600">{{ $kunjunganHariIni->where('status', 'sedang_diperiksa')->count() }}</p>
                        <p class="mt-1 text-xs text-blue-700 font-medium">Diperiksa</p>
                    </div>
                    <div class="rounded-2xl bg-green-50 border border-green-100 px-4 py-4">
                        <p id="count-selesai" class="text-2xl font-bold text-green-600">{{ $kunjunganHariIni->where('status', 'selesai')->count() }}</p>
                        <p class="mt-1 text-xs text-green-700 font-medium">Selesai</p>
                    </div>
                </div>
            </div>

            {{-- Kanan: Papan antrian --}}
            <div class="rounded-2xl bg-white shadow-sm border border-gray-100 overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-teal-500 to-teal-600">
                    <div class="flex items-center gap-2 text-white">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-bold text-sm">Antrian Pasien</span>
                    </div>
                    <span class="text-xs text-teal-100">{{ \Carbon\Carbon::today()->format('d M Y') }}</span>
                </div>

                @if($kunjunganHariIni->isEmpty())
                    <div class="py-14 text-center text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <p class="text-sm font-medium">Belum ada antrian hari ini</p>
                        <p class="text-xs mt-1">Silakan hubungi klinik untuk mendaftar</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-50 max-h-80 overflow-y-auto">
                        @foreach($kunjunganHariIni as $index => $k)
                            @php
                                $firstName = explode(' ', $k->patient->display_name)[0];
                                $statusColor = match($k->status) {
                                    'antrian'          => ['dot' => 'bg-yellow-400', 'badge' => 'bg-yellow-100 text-yellow-800', 'label' => 'Menunggu'],
                                    'sedang_diperiksa' => ['dot' => 'bg-blue-400',   'badge' => 'bg-blue-100 text-blue-800',   'label' => 'Diperiksa'],
                                    'selesai'          => ['dot' => 'bg-green-400',  'badge' => 'bg-green-100 text-green-800', 'label' => 'Selesai'],
                                    default            => ['dot' => 'bg-gray-400',   'badge' => 'bg-gray-100 text-gray-800',   'label' => $k->status],
                                };
                            @endphp
                            <div class="flex items-center gap-4 px-5 py-3.5 {{ $k->status === 'sedang_diperiksa' ? 'bg-blue-50/50' : '' }}">
                                {{-- Nomor antrian --}}
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold shrink-0
                                    {{ $k->status === 'sedang_diperiksa' ? 'bg-blue-500 text-white' : 'bg-teal-50 text-teal-700 border border-teal-100' }}">
                                    {{ $index + 1 }}
                                </div>

                                {{-- Nama (hanya nama depan untuk privasi) --}}
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-slate-900">{{ $firstName }}
                                        @if($k->status === 'sedang_diperiksa')
                                            <span class="text-xs text-blue-500 font-medium">(Sedang dilayani)</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-400">{{ $k->tanggal_kunjungan->format('d M Y') }}</p>
                                </div>

                                {{-- Status --}}
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColor['badge'] }} shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $statusColor['dot'] }}"></span>
                                    {{ $statusColor['label'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="px-5 py-3 border-t border-gray-100 bg-gray-50 text-center">
                    <p class="text-xs text-gray-400">Halaman ini diperbarui setiap kali Anda refresh</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-slate-50 py-16 lg:py-20">
    <div class="mx-auto max-w-7xl px-6 lg:px-12">
        <div class="grid gap-6 lg:grid-cols-[1.2fr_0.8fr] lg:items-end">
            <div>
                <p class="text-sm font-semibold text-teal-500">Patient Says</p>
                <h2 class="mt-3 max-w-2xl text-3xl font-bold leading-tight text-slate-900 sm:text-4xl">Our Services In The Eyes Of Our Patients</h2>
            </div>
            <p class="max-w-lg text-base leading-8 text-gray-500">Opini dari pasien yang telah merasakan pelayanan kesehatan gigi kami secara langsung.</p>
        </div>

        <div class="mt-12 grid gap-6 lg:grid-cols-3">
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="flex items-center gap-4"><img src="https://placehold.co/56x56" alt="Edward Elric" class="h-14 w-14 rounded-full object-cover"><div><h3 class="font-bold text-slate-900">Edward Elric</h3><p class="text-sm text-gray-500">Freelance</p></div></div>
                <p class="mt-4 text-yellow-400">★★★★★</p>
                <p class="mt-4 text-sm leading-7 text-gray-500">Membuat janji sangat mudah dan proses perawatannya terasa nyaman. Dokternya komunikatif dan menjelaskan setiap tindakan dengan jelas.</p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="flex items-center gap-4"><img src="https://placehold.co/56x56" alt="William Smith" class="h-14 w-14 rounded-full object-cover"><div><h3 class="font-bold text-slate-900">William Smith</h3><p class="text-sm text-gray-500">Photographer</p></div></div>
                <p class="mt-4 text-yellow-400">★★★★★</p>
                <p class="mt-4 text-sm leading-7 text-gray-500">Kliniknya bersih, modern, dan fasilitasnya lengkap. Saya merasa tenang selama tindakan karena timnya sangat profesional.</p>
            </div>
            <div class="rounded-2xl bg-white p-6 shadow-sm">
                <div class="flex items-center gap-4"><img src="https://placehold.co/56x56" alt="Nikolas Nolan" class="h-14 w-14 rounded-full object-cover"><div><h3 class="font-bold text-slate-900">Nikolas Nolan</h3><p class="text-sm text-gray-500">UI Designer</p></div></div>
                <p class="mt-4 text-yellow-400">★★★★★</p>
                <p class="mt-4 text-sm leading-7 text-gray-500">Saya terbantu sekali dengan konsultasi yang detail dan pelayanan yang ramah. Hasil perawatannya memuaskan dan terasa sangat worth it.</p>
            </div>
        </div>
    </div>
</section>
{{-- Popup notification container --}}
<div id="notif-container" class="fixed bottom-6 right-6 z-50 flex flex-col gap-3 max-w-sm pointer-events-none"></div>

@endsection

@push('scripts')
<script>
(function () {
    const pageLoadTime = new Date().toISOString();

    @php
        $latestKunjungan = $kunjunganHariIni->last();
        $initialPatients = $kunjunganHariIni->values()->map(fn($k, $i) => [
            'no'         => $i + 1,
            'nama'       => $k->patient->display_name,
            'status'     => $k->status,
            'updated_at' => $k->updated_at->toISOString(),
        ]);
    @endphp

    let lastCreatedAt = "{{ $latestKunjungan ? $latestKunjungan->created_at->toISOString() : '' }}";
    let lastUpdatedAt = "{{ $latestKunjungan ? $latestKunjungan->updated_at->toISOString() : '' }}";
    let knownPatients = {!! json_encode($initialPatients) !!}; // [{no, nama, status, updated_at}]

    const container = document.getElementById('notif-container');

    // ── Suara pengumuman ──────────────────────────────────────────────────────
    function speak(text) {
        if (!('speechSynthesis' in window)) return;
        window.speechSynthesis.cancel();
        const u = new SpeechSynthesisUtterance(text);
        u.lang  = 'id-ID';
        u.rate  = 0.9;
        u.pitch = 1;
        window.speechSynthesis.speak(u);
    }

    // ── Popup notifikasi ──────────────────────────────────────────────────────
    function showNotif(type, no, nama, pesan, subtext) {
        const cfg = {
            panggil : { border:'border-blue-300',  bg:'bg-blue-600',   noBg:'bg-white text-blue-600', icon:'🔔' },
            baru    : { border:'border-teal-300',   bg:'bg-teal-600',   noBg:'bg-white text-teal-600', icon:'👤' },
            selesai : { border:'border-green-300',  bg:'bg-green-600',  noBg:'bg-white text-green-600',icon:'✓'  },
        };
        const c  = cfg[type] || cfg.baru;
        const el = document.createElement('div');
        el.className = `notif-enter pointer-events-auto w-96 rounded-2xl overflow-hidden shadow-2xl border ${c.border}`;
        el.innerHTML = `
            <div class="${c.bg} px-5 py-3 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">${c.icon}</span>
                    <div>
                        <p class="text-white text-xs font-semibold uppercase tracking-widest opacity-80">${pesan}</p>
                        <p class="text-white text-xl font-black leading-tight">No. ${no} — ${nama}</p>
                    </div>
                </div>
                <button class="dismiss-btn text-white/60 hover:text-white transition p-1 rounded-lg hover:bg-white/10 ml-2 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <div class="bg-white px-5 py-2.5">
                <p class="text-sm text-gray-500">${subtext}</p>
            </div>`;

        el.querySelector('.dismiss-btn').addEventListener('click', () => dismiss(el));
        container.appendChild(el);
        setTimeout(() => dismiss(el), 8000);
    }

    function dismiss(el) {
        if (!el || el._out) return;
        el._out = true;
        el.classList.remove('notif-enter');
        el.classList.add('notif-exit');
        setTimeout(() => el.remove(), 300);
    }

    // ── Update counter di halaman ─────────────────────────────────────────────
    function updateCounters(data) {
        const b = document.getElementById('antrian-badge-count');
        const m = document.getElementById('count-menunggu');
        const d = document.getElementById('count-diperiksa');
        const s = document.getElementById('count-selesai');
        if (b) b.textContent = data.total + ' Pasien Terdaftar Hari Ini';
        if (m) m.textContent = data.menunggu;
        if (d) d.textContent = data.diperiksa;
        if (s) s.textContent = data.selesai;
    }

    // ── Polling ───────────────────────────────────────────────────────────────
    async function poll() {
        try {
            const res  = await fetch('/api/antrian-status');
            if (!res.ok) return;
            const data = await res.json();

            // 1. Pasien baru masuk (created_at lebih baru)
            if (data.latest_created_at && data.latest_created_at > lastCreatedAt && data.latest_created_at > pageLoadTime) {
                const newOnes = data.patients.filter(p => !knownPatients.find(k => k.no === p.no));
                newOnes.forEach(p => {
                    showNotif('baru', p.no, p.nama, 'Pasien Baru Mendaftar', 'Silakan menunggu, antrian Anda telah terdaftar.');
                    speak(`Pasien baru nomor antrian ${p.no}, ${p.nama}, telah mendaftar.`);
                });
                lastCreatedAt = data.latest_created_at;
                knownPatients = data.patients;
                updateCounters(data);
                return;
            }

            // 2. Status berubah — deteksi per-pasien
            if (data.latest_updated_at && data.latest_updated_at > lastUpdatedAt && data.latest_updated_at > pageLoadTime) {
                data.patients.forEach(p => {
                    const prev = knownPatients.find(k => k.no === p.no);
                    if (!prev || prev.status === p.status) return;

                    if (p.status === 'sedang_diperiksa') {
                        showNotif('panggil', p.no, p.nama, 'Dipanggil — Silakan Masuk', 'Segera menuju ruang periksa. Dokter siap melayani Anda.');
                        speak(`Nomor antrian ${p.no}, ${p.nama}, silakan masuk ke ruang periksa.`);
                    } else if (p.status === 'selesai') {
                        showNotif('selesai', p.no, p.nama, 'Selesai Diperiksa', 'Terima kasih telah berkunjung. Semoga lekas sembuh.');
                        speak(`Nomor antrian ${p.no}, ${p.nama}, terima kasih. Pemeriksaan selesai.`);
                    }
                });
                lastUpdatedAt = data.latest_updated_at;
                knownPatients = data.patients;
                updateCounters(data);
            }

        } catch (e) { /* abaikan jika offline */ }
    }

    setInterval(poll, 10000);
})();
</script>
@endpush