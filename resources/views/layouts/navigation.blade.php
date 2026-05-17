<!-- Mobile Overlay -->
<div x-show="sidebarOpen" 
     x-transition.opacity 
     @click="sidebarOpen = false" 
     class="fixed inset-0 bg-gray-900/50 z-40 md:hidden backdrop-blur-sm"
     style="display: none;"></div>

<!-- Sidebar -->
<nav :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}" 
     class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 z-50 transform transition-transform duration-300 md:translate-x-0 flex flex-col">
    
    <!-- Sidebar Header / Logo -->
    <div class="h-20 flex items-center justify-between px-6 border-b border-gray-100 bg-white">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <div class="h-10 w-10 bg-teal-50 rounded-xl flex items-center justify-center border border-teal-100 shadow-sm overflow-hidden">
                <img src="{{ asset('img/logo.png') }}" alt="Logo" class="h-8 w-8 object-contain" onerror="this.onerror=null;">
            </div>
            <div class="flex flex-col">
                <span class="font-black text-lg text-gray-900 leading-none tracking-tight">Klinik Gigi</span>
                <span class="text-[10px] font-bold text-teal-600 uppercase tracking-widest mt-1">Management Syst.</span>
            </div>
        </a>
        <button @click="sidebarOpen = false" class="md:hidden text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Scrollable Nav Links -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
        <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex w-full items-center px-4 py-3 rounded-xl mb-1 transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-teal-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
            <span class="font-semibold">{{ __('Dashboard') }}</span>
        </x-nav-link>

        <x-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.*')" class="flex w-full items-center px-4 py-3 rounded-xl mb-1 transition-all duration-200 {{ request()->routeIs('pos.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('pos.*') ? 'text-teal-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span class="font-bold {{ request()->routeIs('pos.*') ? 'text-teal-700' : '' }}">{{ __('POS / Kasir') }}</span>
        </x-nav-link>

        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Master Data</p>
        </div>

        <x-nav-link :href="route('patients.index')" :active="request()->routeIs('patients.*')" class="flex w-full items-center px-4 py-3 rounded-xl mb-1 transition-all duration-200 {{ request()->routeIs('patients.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('patients.*') ? 'text-teal-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <span class="font-semibold">{{ __('Pasien') }}</span>
        </x-nav-link>

        <x-nav-link :href="route('treatments.index')" :active="request()->routeIs('treatments.*')" class="flex w-full items-center px-4 py-3 rounded-xl mb-1 transition-all duration-200 {{ request()->routeIs('treatments.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('treatments.*') ? 'text-teal-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            <span class="font-semibold">{{ __('Tindakan') }}</span>
        </x-nav-link>

        <x-nav-link :href="route('items.index')" :active="request()->routeIs('items.*')" class="flex w-full items-center px-4 py-3 rounded-xl mb-1 transition-all duration-200 {{ request()->routeIs('items.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('items.*') ? 'text-teal-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <span class="font-semibold">{{ __('Item/Bahan') }}</span>
        </x-nav-link>

        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Rekam Medis</p>
        </div>

        <x-nav-link :href="route('kunjungan.index')" :active="request()->routeIs('kunjungan.*')" class="flex w-full items-center px-4 py-3 rounded-xl mb-1 transition-all duration-200 {{ request()->routeIs('kunjungan.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('kunjungan.*') ? 'text-teal-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <span class="font-semibold">{{ __('Kunjungan') }}</span>
        </x-nav-link>

        <x-nav-link :href="route('rekam-medis.index')" :active="request()->routeIs('rekam-medis.*')" class="flex w-full items-center px-4 py-3 rounded-xl mb-1 transition-all duration-200 {{ request()->routeIs('rekam-medis.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('rekam-medis.*') ? 'text-teal-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span class="font-semibold">{{ __('Rekam Medis') }}</span>
        </x-nav-link>

        <div class="pt-4 pb-2">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Inventory & Reports</p>
        </div>

        <x-nav-link :href="route('stock.index')" :active="request()->routeIs('stock.*')" class="flex w-full items-center px-4 py-3 rounded-xl mb-1 transition-all duration-200 {{ request()->routeIs('stock.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('stock.*') ? 'text-teal-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            <span class="font-semibold">{{ __('Stok / Mutasi') }}</span>
        </x-nav-link>

        <x-nav-link :href="route('reports.revenue')" :active="request()->routeIs('reports.*')" class="flex w-full items-center px-4 py-3 rounded-xl mb-1 transition-all duration-200 {{ request()->routeIs('reports.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('reports.*') ? 'text-teal-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            <span class="font-semibold">{{ __('Laporan') }}</span>
        </x-nav-link>

        <x-nav-link :href="route('settings.index')" :active="request()->routeIs('settings.*')" class="flex w-full items-center px-4 py-3 rounded-xl mb-1 transition-all duration-200 {{ request()->routeIs('settings.*') ? 'bg-teal-50 text-teal-700 shadow-sm' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 mr-3 {{ request()->routeIs('settings.*') ? 'text-teal-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span class="font-semibold">{{ __('Pengaturan') }}</span>
        </x-nav-link>
    </div>

    <!-- Sidebar Footer / User -->
    <div class="border-t border-gray-100 p-6 bg-gray-50/50">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-12 h-12 rounded-2xl bg-teal-600 flex items-center justify-center text-white font-black shadow-lg border-2 border-white">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-black text-gray-900 truncate leading-tight">{{ Auth::user()->name }}</p>
                <div class="flex items-center gap-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></div>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
        
        <div class="flex flex-col gap-2">
            <x-nav-link :href="route('profile.edit')" class="w-full justify-center flex py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-bold shadow-sm hover:shadow-md hover:border-teal-200 transition-all">
                Settings Profile
            </x-nav-link>
            
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex justify-center py-2.5 bg-gray-900 text-white rounded-xl text-xs font-black hover:bg-teal-600 transition-all shadow-lg active:scale-95">
                    LOGOUT
                </button>
            </form>
        </div>
    </div>
</nav>
