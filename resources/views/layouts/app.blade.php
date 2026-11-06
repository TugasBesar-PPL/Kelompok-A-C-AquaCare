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
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col">

    <div class="flex flex-1 min-h-screen">
        <!-- Sidebar Navigation (Matching Mockup Gambar 8) -->
        <aside class="w-64 bg-navy-900 text-slate-300 flex flex-col shrink-0">
            <!-- Brand Logo -->
            <div class="h-16 px-6 flex items-center gap-3 border-b border-slate-800">
                <div class="w-9 h-9 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold text-xl">
                    <i class="fa-solid fa-droplet"></i>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-lg text-white tracking-wide">AquaCare</span>
                    <span class="text-[10px] text-cyan-400 uppercase font-semibold tracking-wider">Air Bersih & Sanitasi</span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-5 space-y-1.5 text-sm font-medium">
                <a href="{{ route('dashboard') }}" 
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-lg transition-colors {{ request()->routeIs('dashboard') || request()->is('/') ? 'bg-cyan-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-base"></i>
                    <span>Dashboard</span>
                </a>

                <a href="#" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-faucet-drip w-5 text-center text-base"></i>
                        <span>Titik Sumber Air</span>
                    </div>
                    <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">PBI-01</span>
                </a>

                <a href="#" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-flask-vial w-5 text-center text-base"></i>
                        <span>Pengukuran</span>
                    </div>
                    <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">PBI-03</span>
                </a>

                <!-- PBI-07: Verifikasi Laporan Warga -->
                <div class="pt-1 pb-1">
                    <div class="px-3.5 pb-1 text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                        Modul Pelaporan (Fathir)
                    </div>
                    <a href="{{ route('reports.verification') }}" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-lg transition-colors {{ request()->routeIs('reports.verification') ? 'bg-cyan-600 text-white shadow-sm' : 'hover:bg-slate-800 text-slate-300 hover:text-white' }}">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid fa-clipboard-check w-5 text-center text-base"></i>
                            <span class="font-semibold">Verifikasi Laporan</span>
                        </div>
                        <span class="text-xs px-2 py-0.5 rounded-full font-bold {{ request()->routeIs('reports.verification') ? 'bg-white text-cyan-700' : 'bg-amber-500 text-white' }}">
                            {{ \App\Models\LaporanMasalahAir::where('status_laporan', 'Menunggu')->count() }}
                        </span>
                    </a>
                </div>

                <a href="#" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-calendar-check w-5 text-center text-base"></i>
                        <span>Jadwal & Baku Mutu</span>
                    </div>
                    <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">PBI-09/18</span>
                </a>

                <a href="#" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-map-location-dot w-5 text-center text-base"></i>
                        <span>Peta Sebaran</span>
                    </div>
                    <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">PBI-10</span>
                </a>

                <a href="#" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-chart-line w-5 text-center text-base"></i>
                        <span>Statistik</span>
                    </div>
                    <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">PBI-14</span>
                </a>

                <a href="#" class="flex items-center justify-between px-3.5 py-2.5 rounded-lg text-slate-400 hover:bg-slate-800 hover:text-slate-200 transition-colors">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-clock-rotate-left w-5 text-center text-base"></i>
                        <span>Log Aktivitas</span>
                    </div>
                    <span class="text-[10px] bg-slate-800 text-slate-400 px-1.5 py-0.5 rounded">PBI-17</span>
                </a>
            </nav>

            <!-- Bottom User & Role Info -->
            <div class="p-4 border-t border-slate-800 bg-navy-950/60 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-cyan-700 text-white font-bold text-xs flex items-center justify-center">
                        FA
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-semibold text-white">Fathir A. (Admin)</span>
                        <span class="text-[10px] text-slate-400">PBI 07, 09, 16</span>
                    </div>
                </div>
                <a href="{{ route('logout') }}" title="Keluar" class="text-slate-400 hover:text-rose-400 transition-colors p-1.5 rounded">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-x-hidden">
            <!-- Top Navbar (Matching Mockup Gambar 8) -->
            <header class="h-16 bg-white border-b border-slate-200 px-8 flex items-center justify-between shadow-xs sticky top-0 z-30">
                <!-- Breadcrumbs -->
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-slate-400">Dashboard</span>
                    <span class="text-slate-300">/</span>
                    <span class="font-medium text-slate-700">@yield('breadcrumb', 'Ringkasan')</span>
                </div>

                <!-- Right Action Bar -->
                <div class="flex items-center gap-4">
                    <!-- Notification Badge (Mockup Gambar 8: "4 notifikasi") -->
                    <div class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs px-3 py-1.5 rounded-full cursor-pointer transition-colors border border-slate-200">
                        <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                        <span class="font-semibold">4 notifikasi</span>
                    </div>

                    <!-- User Pill Badge (Mockup Gambar 8: "AD Admin") -->
                    <div class="flex items-center gap-2.5 pl-3 border-l border-slate-200">
                        <div class="w-8 h-8 rounded-full bg-blue-600 text-white font-bold text-xs flex items-center justify-center shadow-xs">
                            AD
                        </div>
                        <span class="text-sm font-semibold text-slate-700">Admin</span>
                    </div>
                </div>
            </header>

            <!-- Notification Toast / Flash message -->
            @if(session('success'))
            <div class="mx-8 mt-6 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-start justify-between shadow-xs">
                <div class="flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 text-lg"></i>
                    <div>
                        <p class="font-medium text-sm">{{ session('success') }}</p>
                    </div>
                </div>
                <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
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
                <span>AquaCare &copy; 2026 - Platform Monitoring & Pelaporan Air Bersih (SDG 6)</span>
                <span>PBI-07: Verifikasi dan Penugasan Laporan &bull; PIC: Fathir Aulia Helmi</span>
            </footer>
        </div>
    </div>

    @yield('scripts')
</body>
</html>

