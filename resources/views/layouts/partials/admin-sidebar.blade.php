{{-- Sidebar Admin AquaCare --}}
<aside id="admin-sidebar" class="fixed top-0 left-0 z-40 w-60 h-screen bg-[#1a3a5c] text-white flex flex-col transition-transform duration-300 -translate-x-full lg:translate-x-0">
    {{-- Logo --}}
    <div class="flex items-center gap-2.5 px-5 py-4 border-b border-white/10">
        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-white/15">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9c1.66 0 3.22-.45 4.56-1.24M12 3v9l6.36 3.64" />
            </svg>
        </div>
        <span class="text-lg font-bold tracking-tight">AquaCare</span>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        @php
            $currentRoute = request()->route()?->getName() ?? '';
            $menuItems = [
                ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'dashboard'],
                ['route' => '#', 'label' => 'Titik Sumber Air', 'icon' => 'water'],
                ['route' => '#', 'label' => 'Pengukuran', 'icon' => 'measurement'],
                ['route' => '#', 'label' => 'Laporan Warga', 'icon' => 'report'],
                ['route' => '#', 'label' => 'Jadwal & Baku Mutu', 'icon' => 'schedule'],
                ['route' => '#', 'label' => 'Peta Sebaran', 'icon' => 'map'],
                ['route' => '#', 'label' => 'Statistik', 'icon' => 'stats'],
                ['route' => 'admin.audit-logs.index', 'label' => 'Log Aktivitas', 'icon' => 'log'],
            ];
        @endphp

        @foreach ($menuItems as $item)
            @php
                $isActive = $item['route'] !== '#' && $currentRoute === $item['route'];
                $href = $item['route'] !== '#' ? route($item['route']) : '#';
            @endphp
            <a href="{{ $href }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                      {{ $isActive
                          ? 'bg-white/15 text-white'
                          : 'text-white/70 hover:bg-white/8 hover:text-white' }}">
                {{-- Icons --}}
                @switch($item['icon'])
                    @case('dashboard')
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1h-2z"/></svg>
                        @break
                    @case('water')
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21c-4.418 0-8-3.134-8-7 0-4.418 8-11 8-11s8 6.582 8 11c0 3.866-3.582 7-8 7z"/></svg>
                        @break
                    @case('measurement')
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        @break
                    @case('report')
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        @break
                    @case('schedule')
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @break
                    @case('map')
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        @break
                    @case('stats')
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        @break
                    @case('log')
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        @break
                @endswitch
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>
</aside>
