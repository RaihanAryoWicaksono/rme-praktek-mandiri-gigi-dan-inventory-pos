<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        
        <style>
            :root {
                --brand-teal: #20B2AA;
                --brand-teal-light: #e0f2f1;
                --brand-teal-dark: #1a948e;
            }
            body { font-family: 'Inter', sans-serif !important; }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- jQuery & DataTables -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        
        <style>
            [x-cloak] { display: none !important; }
            
            /* ── DataTables Controls ── */
            div.dataTables_wrapper {
                font-family: 'Inter', sans-serif;
            }

            /* Top bar: length (left) + filter (right) */
            div.dataTables_wrapper div.dataTables_length,
            div.dataTables_wrapper div.dataTables_filter {
                padding: 1rem 1.25rem;
                background: #f9fafb;
                border-bottom: 1px solid #f3f4f6;
            }
            div.dataTables_wrapper div.dataTables_length {
                float: left;
            }
            div.dataTables_wrapper div.dataTables_filter {
                float: right;
            }

            div.dataTables_wrapper div.dataTables_length label,
            div.dataTables_wrapper div.dataTables_filter label {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 0.8rem;
                font-weight: 500;
                color: #6b7280;
                margin: 0;
                white-space: nowrap;
            }

            div.dataTables_wrapper div.dataTables_length select {
                display: inline-block;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                padding: 0.35rem 2rem 0.35rem 0.6rem;
                font-size: 0.8rem;
                font-weight: 600;
                color: #374151;
                background: white url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E") right 0.5rem center / 1rem no-repeat;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
                cursor: pointer;
                transition: border-color 0.2s;
            }
            div.dataTables_wrapper div.dataTables_length select:focus {
                outline: none;
                border-color: var(--brand-teal);
                box-shadow: 0 0 0 3px rgba(32, 178, 170, 0.12);
            }

            div.dataTables_wrapper div.dataTables_filter input {
                display: inline-block;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                padding: 0.35rem 0.75rem;
                font-size: 0.8rem;
                color: #374151;
                background: white;
                margin: 0;
                transition: border-color 0.2s, box-shadow 0.2s;
                min-width: 200px;
            }
            div.dataTables_wrapper div.dataTables_filter input:focus {
                outline: none;
                border-color: var(--brand-teal);
                box-shadow: 0 0 0 3px rgba(32, 178, 170, 0.12);
            }

            /* Bottom bar: info (left) + paginate (right) */
            div.dataTables_wrapper div.dataTables_info {
                padding: 0.85rem 1.25rem;
                font-size: 0.78rem;
                color: #9ca3af;
                float: left;
                clear: both;
            }
            div.dataTables_wrapper div.dataTables_paginate {
                padding: 0.6rem 1.25rem;
                float: right;
            }
            div.dataTables_wrapper div.dataTables_paginate .paginate_button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                min-width: 2rem;
                height: 2rem;
                padding: 0 0.5rem;
                margin: 0 0.1rem;
                border-radius: 0.4rem;
                border: 1px solid transparent;
                font-size: 0.8rem;
                font-weight: 500;
                color: #6b7280;
                cursor: pointer;
                transition: all 0.15s;
                text-decoration: none;
            }
            div.dataTables_wrapper div.dataTables_paginate .paginate_button:hover:not(.disabled) {
                background: #f0fafa;
                border-color: #e0f2f1;
                color: var(--brand-teal);
            }
            div.dataTables_wrapper div.dataTables_paginate .paginate_button.current,
            div.dataTables_wrapper div.dataTables_paginate .paginate_button.current:hover {
                background: var(--brand-teal);
                border-color: var(--brand-teal);
                color: white !important;
                font-weight: 700;
            }
            div.dataTables_wrapper div.dataTables_paginate .paginate_button.disabled,
            div.dataTables_wrapper div.dataTables_paginate .paginate_button.disabled:hover {
                color: #d1d5db !important;
                cursor: default;
            }
            div.dataTables_wrapper::after { content: ''; display: table; clear: both; }

            /* Table */
            table.dataTable { border-collapse: collapse !important; border-spacing: 0 !important; width: 100% !important; margin: 0 !important; }
            table.dataTable thead th {
                border-bottom: 2px solid #f3f4f6 !important;
                background-color: #f9fafb !important;
                color: #6b7280 !important;
                font-weight: 700 !important;
                text-transform: uppercase !important;
                letter-spacing: 0.05em !important;
                font-size: 0.75rem !important;
                padding: 0.85rem 1rem !important;
            }
            table.dataTable thead th.sorting::before,
            table.dataTable thead th.sorting::after,
            table.dataTable thead th.sorting_asc::before,
            table.dataTable thead th.sorting_asc::after,
            table.dataTable thead th.sorting_desc::before,
            table.dataTable thead th.sorting_desc::after { opacity: 0.4; }
            table.dataTable tbody td {
                padding: 0.85rem 1rem !important;
                border-bottom: 1px solid #f3f4f6 !important;
                vertical-align: middle !important;
            }
            table.dataTable tbody tr:last-child td { border-bottom: none !important; }

            /* Kolom AKSI: selalu center, tanpa sort arrow */
            table.dataTable thead th.col-aksi,
            table.dataTable thead th.col-aksi.sorting,
            table.dataTable thead th.col-aksi.sorting_asc,
            table.dataTable thead th.col-aksi.sorting_desc {
                text-align: center !important;
                padding-right: 1rem !important;
            }
            table.dataTable thead th.col-aksi::before,
            table.dataTable thead th.col-aksi::after { display: none !important; }
            table.dataTable tbody td.col-aksi { text-align: center !important; }
            
            /* Theme overrides */
            .bg-indigo-600, .bg-indigo-500 { background-color: var(--brand-teal) !important; }
            .hover\:bg-indigo-700:hover, .hover\:bg-indigo-600:hover { background-color: var(--brand-teal-dark) !important; }
            .text-indigo-600, .text-indigo-500 { color: var(--brand-teal) !important; }
            .text-indigo-700 { color: var(--brand-teal-dark) !important; }
            .border-indigo-600, .border-indigo-500 { border-color: var(--brand-teal) !important; }
            .focus\:ring-indigo-500:focus { --tw-ring-color: var(--brand-teal) !important; }
            .bg-indigo-50 { background-color: var(--brand-teal-light) !important; }
            .decoration-indigo-500 { text-decoration-color: var(--brand-teal) !important; }
            
            /* Custom Spacing & Polish */
            .main-content-card {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.4);
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
                border-radius: 1.5rem;
            }
            
            /* Scrollbar */
            ::-webkit-scrollbar { width: 6px; height: 6px; }
            ::-webkit-scrollbar-track { background: transparent; }
            ::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
            ::-webkit-scrollbar-thumb:hover { background: #9ca3af; }
        </style>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gradient-to-br from-[#fdfdfd] via-[#f0fafa] to-[#e6f4f4] min-h-screen attachment-fixed">
        <div class="min-h-screen flex" x-data="{ sidebarOpen: false }">
            
            <!-- Sidebar Navigation -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-w-0 md:ml-64 transition-all duration-300">
                <!-- Mobile Header -->
                <div class="md:hidden flex items-center justify-between p-4 bg-white border-b border-gray-200">
                    <div class="flex items-center">
                        <h1 class="font-bold text-xl text-indigo-600">Klinik Gigi</h1>
                    </div>
                    <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow-sm border-b border-gray-200 z-50 relative">
                        <div class="py-6 px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                            {{ $header }}
                            <div class="hidden md:flex items-center gap-6" x-data="{ 
                                isOpen: false, 
                                notifications: [],
                                removeNotif(id) {
                                    this.notifications = this.notifications.filter(n => n.id !== id);
                                }
                            }">
                                @php
                                    $__alertSetting = \App\Models\Setting::where('key', 'min_stock_alert')->value('value') ?? 10;
                                    
                                    $__lowStock = \App\Models\Item::withSum('batches', 'stock')
                                        ->where('is_active', true)->get()
                                        ->filter(fn($i) => ($i->batches_sum_stock ?? 0) < $__alertSetting)
                                        ->map(fn($i) => [
                                            'id' => 'low_'.$i->id,
                                            'title' => 'Stok Menipis',
                                            'message' => $i->name . ' (Sisa ' . ($i->batches_sum_stock ?? 0) . ' ' . $i->unit_use . ')',
                                            'type' => 'warning'
                                        ])->values()->toArray();

                                    $__exp = \App\Models\ItemBatch::with('item')
                                        ->where('stock', '>', 0)
                                        ->where('expiry_date', '<=', now()->addDays(30))
                                        ->get()
                                        ->map(function($b) {
                                            $isExpired = \Carbon\Carbon::parse($b->expiry_date)->isPast();
                                            return [
                                                'id' => 'exp_'.$b->id,
                                                'title' => $isExpired ? 'Sudah Expired' : 'Hampir Expired',
                                                'message' => $b->item->name . ' (Exp: ' . \Carbon\Carbon::parse($b->expiry_date)->format('d/m/Y') . ')',
                                                'type' => $isExpired ? 'error' : 'warning'
                                            ];
                                        })->values()->toArray();

                                    $__allNotif = array_merge($__lowStock, $__exp);
                                @endphp

                                <div class="relative flex items-center h-full" x-init="notifications = {{ json_encode($__allNotif) }}">
                                    <!-- Bell Icon Trigger -->
                                    <button @click="isOpen = !isOpen" @click.away="isOpen = false" title="Notifikasi Stok/Expired" class="relative text-gray-400 hover:text-red-500 transition focus:outline-none">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                        <span x-show="notifications.length > 0" x-text="notifications.length" class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-red-600 text-[10px] font-bold text-white ring-2 ring-white" style="display: none;"></span>
                                    </button>

                                    <!-- Dropdown Notification List -->
                                    <div x-show="isOpen" 
                                         x-transition.opacity
                                         class="absolute right-0 top-full mt-4 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden cursor-default" 
                                         style="display: none;">
                                         
                                         <div class="bg-gray-50 border-b border-gray-100 px-4 py-3 flex justify-between items-center">
                                            <span class="font-bold text-gray-700 text-sm">Notifikasi</span>
                                            <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold" x-show="notifications.length > 0" x-text="notifications.length + ' Baru'"></span>
                                         </div>

                                         <div class="max-h-80 overflow-y-auto">
                                             <template x-for="notif in notifications" :key="notif.id">
                                                 <div class="p-4 border-b border-gray-50 relative hover:bg-gray-50/50 transition group flex flex-col items-start pr-8">
                                                     <button @click.stop="removeNotif(notif.id)" title="Hapus Notifikasi" class="absolute top-2 right-2 text-gray-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition p-1 rounded-full hover:bg-gray-200">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                     </button>
                                                     <p class="text-xs font-black mb-1" :class="notif.type == 'error' ? 'text-red-600' : 'text-orange-500'" x-text="notif.title"></p>
                                                     <p class="text-sm text-gray-600 leading-tight" x-text="notif.message"></p>
                                                 </div>
                                             </template>
                                             <div x-show="notifications.length === 0" class="p-6 text-center text-gray-400 text-sm italic">
                                                 Semua aman. Tidak ada peringatan.
                                             </div>
                                         </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 border-l border-gray-200 pl-6 h-full">
                                    <span class="text-sm font-bold text-gray-700">Halo, {{ Auth::user()->name }}</span>
                                </div>
                            </div>
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')

        <script>
            $(document).ready(function() {
                $('.datatable').each(function() {
                    let emptyMsg = $(this).data('empty-message') || 'Tidak ada data.';
                    
                    $(this).DataTable({
                        language: {
                            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/id.json'
                        },
                        pageLength: 25,
                        ordering: true,
                        responsive: true,
                        columnDefs: [
                            { targets: '.col-aksi', orderable: false, className: 'col-aksi' }
                        ],
                        drawCallback: function(settings) {
                            var api = this.api();
                            if (api.data().length === 0) {
                                $(api.table().body()).find('.dataTables_empty').html('<span class="text-gray-400 italic">' + emptyMsg + '</span>');
                            }
                        }
                    });
                });
            });
        </script>
    </body>
</html>
