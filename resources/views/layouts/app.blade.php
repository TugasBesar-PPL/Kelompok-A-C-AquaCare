<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AquaCare - Platform Monitoring dan Pelaporan Air Bersih')</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        aqua: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        navy: {
                            800: '#1e293b',
                            850: '#172234',
                            900: '#0f172a',
                            950: '#090d16',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .custom-popup .leaflet-popup-content-wrapper {
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            padding: 4px;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col">

    <div class="flex flex-1 min-h-screen">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-navy-900 text-slate-300 flex flex-col shrink-0" style="background-color: #0f172a;">
            <!-- Brand Logo -->
            <div class="h-16 px-6 flex items-center gap-3 border-b border-slate-800">
                <div class="w-9 h-9 rounded-lg flex items-center justify-center font-bold text-xl" style="background-color: rgba(14,165,233,0.2); color: #38bdf8;">
                    <i class="fa-solid fa-droplet"></i>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-lg text-white tracking-wide">AquaCare</span>
                    <span class="text-[10px] uppercase font-semibold tracking-wider" style="color: #38bdf8;">Air Bersih &amp; Sanitasi</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-5 space-y-1.5 text-sm font-medium">

                <!-- Modul Fadhlan: PBI-01 & PBI-12 -->
                <div class="pt-1 pb-1 space-y-1">
                    <div class="px-3.5 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                        Modul Fadhlan (PBI-01, 12)
                    </div>
                    <a href="{{ route('water-points.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-lg transition-colors {{ request()->routeIs('water-points.*') ? 'text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}"
                       @if(request()->routeIs('water-points.*')) style="background-color: #0284c7;" @endif>
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-faucet-drip w-5 text-center text-base"></i>
                            <span>Titik Sumber Air</span>
                        </div>
                        <span class="text-[10px] px-1.5 py-0.5 rounded" style="background-color: #1e293b; color: #94a3b8;">PBI-01</span>
                    </a>
                    <a href="{{ route('announcements.index') }}"
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-lg transition-colors {{ request()->routeIs('announcements.*') && !request()->routeIs('announcements.warga*') ? 'text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}"
                       @if(request()->routeIs('announcements.*') && !request()->routeIs('announcements.warga*')) style="background-color: #0284c7;" @endif>
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-bullhorn w-5 text-center text-base"></i>
                            <span>Pengumuman &amp; Edukasi</span>
                        </div>
                        <span class="text-[10px] px-1.5 py-0.5 rounded" style="background-color: #1e293b; color: #94a3b8;">PBI-12</span>
                    </a>
                </div>

                <div class="border-t border-slate-800 my-2"></div>

                <!-- Portal Warga -->
                <div class="pt-1 pb-1 space-y-1">
                    <div class="px-3.5 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                        Portal Warga
                    </div>
                    <a href="{{ route('announcements.warga') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-colors {{ request()->routeIs('announcements.warga*') ? 'text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}"
                       @if(request()->routeIs('announcements.warga*')) style="background-color: #0284c7;" @endif>
                        <i class="fa-solid fa-book-open w-5 text-center text-base"></i>
                        <span>Edukasi &amp; Info Warga</span>
                    </a>
                </div>

                <div class="border-t border-slate-800 my-2"></div>

                <!-- Sprint 2 (placeholder) -->
                <div class="pt-1 pb-1 space-y-1">
                    <div class="px-3.5 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                        Sprint 2 (Belum Aktif)
                    </div>
                    <a href="#" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg text-slate-600 cursor-not-allowed">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-map-location-dot w-5 text-center text-base"></i>
                            <span>Peta Titik Air</span>
                        </div>
                        <span class="text-[10px] px-1.5 py-0.5 rounded" style="background-color: #1e293b; color: #94a3b8;">PBI-10</span>
                    </a>
                </div>
            </nav>

            <!-- User Info Footer -->
            <div class="p-4 border-t border-slate-800 flex items-center justify-between" style="background-color: rgba(9,13,22,0.6);">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full font-bold text-xs flex items-center justify-center text-white" style="background-color: #0369a1;">
                        FS
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-semibold text-white">Fadhlan S. (Admin)</span>
                        <span class="text-[10px] text-slate-400">PBI-01, PBI-12</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-x-hidden">
            <!-- Top Header -->
            <header class="h-16 bg-white border-b border-slate-200 px-8 flex items-center justify-between shadow-xs sticky top-0 z-30">
                <!-- Breadcrumbs -->
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-slate-400">AquaCare</span>
                    <span class="text-slate-300">/</span>
                    <span class="font-medium text-slate-700">@yield('breadcrumb', 'Dashboard')</span>
                </div>

                <!-- Right Action Bar -->
                <div class="flex items-center gap-4">
                    <!-- User Pill -->
                    <div class="flex items-center gap-2.5 pl-3 border-l border-slate-200">
                        <div class="w-8 h-8 rounded-full font-bold text-xs flex items-center justify-center shadow-xs text-white" style="background-color: #0284c7;">
                            FS
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Fadhlan</span>
                    </div>
                </div>
            </header>

            <!-- Flash Toast -->
            @if(session('success'))
            <div class="mx-8 mt-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                    <p class="font-medium text-sm">{{ session('success') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif

            @if(session('error'))
            <div class="mx-8 mt-6 p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 flex items-start justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg"></i>
                    <p class="font-medium text-sm">{{ session('error') }}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif

            @if($errors->any())
            <div class="mx-8 mt-6 p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 flex items-start justify-between shadow-xs">
                <div class="flex items-start gap-3">
                    <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg mt-0.5"></i>
                    <div>
                        <p class="font-semibold text-sm">Terjadi Kesalahan Validasi:</p>
                        <ul class="list-disc list-inside text-xs mt-1 space-y-0.5 text-rose-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            @endif

            <!-- Main Body -->
            <main class="flex-1 p-8">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="px-8 py-4 border-t border-slate-200 bg-white text-xs text-slate-500 flex justify-between items-center">
                <span>AquaCare &copy; 2026 - Platform Monitoring &amp; Pelaporan Air Bersih (SDG 6)</span>
                <span>PBI-01: Titik Sumber Air &bull; PBI-12: Pengumuman &amp; Edukasi &bull; PIC: Fadhlan S.</span>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

