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
                        cyan: {
                            400: '#22d3ee',
                            500: '#06b6d4',
                            600: '#0891b2',
                            700: '#0e7490',
                        },
                        navy: {
                            800: '#172234',
                            900: '#0f172a',
                            950: '#0b1329',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col">

    <div class="flex flex-1 min-h-screen">
        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-navy-950 text-slate-300 flex flex-col shrink-0 shadow-lg">
            <!-- Brand Logo -->
            <div class="h-20 px-6 flex items-center gap-3 border-b border-slate-800/80">
                <div class="w-10 h-10 rounded-xl bg-cyan-500/20 text-cyan-400 flex items-center justify-center font-bold text-xl shadow-inner">
                    <i class="fa-solid fa-droplet"></i>
                </div>
                <div class="flex flex-col">
                    <span class="font-bold text-xl text-white tracking-tight">AquaCare</span>
                    <span class="text-[11px] text-slate-400 font-medium">
                        @if(Auth::check() && Auth::user()->isAdmin())
                            Panel Administrator
                        @elseif(Auth::check() && Auth::user()->isPetugas())
                            Petugas Lapangan
                        @elseif(Auth::check() && Auth::user()->isWarga())
                            Portal Warga
                        @else
                            Monitoring Air Bersih
                        @endif
                    </span>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-6 space-y-2 text-sm font-medium">
                @auth
                    @if(Auth::user()->isAdmin())
                        <!-- Menu Admin -->
                        <div class="px-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            Pengaturan & Standar
                        </div>
                        <a href="{{ route('baku-mutu.admin.index') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('baku-mutu.admin.*') ? 'bg-cyan-500 text-white shadow-md shadow-cyan-500/20 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="fa-solid fa-sliders w-5 text-center"></i>
                            <span>Baku Mutu Air (PBI-18)</span>
                        </a>

                        <div class="pt-4 px-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            Manajemen Akun
                        </div>
                        <a href="{{ route('admin.users.index') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-cyan-500 text-white shadow-md shadow-cyan-500/20 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="fa-solid fa-users-gear w-5 text-center"></i>
                            <span>Kelola Akun (FD-03)</span>
                        </a>
                    @else
                        <!-- Menu Petugas & Warga -->
                        <div class="px-4 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                            Informasi Kualitas Air
                        </div>
                        <a href="{{ route('baku-mutu.index') }}" 
                           class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('baku-mutu.index') ? 'bg-cyan-500 text-white shadow-md shadow-cyan-500/20 font-semibold' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                            <i class="fa-solid fa-flask-vial w-5 text-center"></i>
                            <span>Standar Baku Mutu Air</span>
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-slate-800/60 transition-all">
                        <i class="fa-solid fa-right-to-bracket w-5 text-center"></i>
                        <span>Masuk ke Akun</span>
                    </a>
                    <a href="{{ route('register') }}" 
                       class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:text-white hover:bg-slate-800/60 transition-all">
                        <i class="fa-solid fa-user-plus w-5 text-center"></i>
                        <span>Daftar Akun Warga</span>
                    </a>
                @endauth
            </nav>

            <!-- Bottom User Card / Logout -->
            @auth
            <div class="p-4 border-t border-slate-800/80 bg-navy-900/40 flex items-center justify-between">
                <div class="flex items-center gap-3 overflow-hidden">
                    <div class="w-9 h-9 rounded-full bg-cyan-600 text-white font-bold text-xs flex items-center justify-center shrink-0">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div class="flex flex-col truncate">
                        <span class="text-xs font-bold text-white truncate">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] text-slate-400 capitalize">{{ Auth::user()->role }}</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Keluar" class="text-slate-400 hover:text-rose-400 p-2 rounded-lg hover:bg-slate-800/80 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </form>
            </div>
            @endauth
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 bg-[#f8fafc]">
            <!-- Top Navbar -->
            <header class="h-20 bg-white border-b border-slate-200/80 px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs">
                <!-- Breadcrumbs -->
                <div class="flex flex-col">
                    <span class="text-[11px] font-bold text-cyan-600 tracking-wider uppercase">
                        AQUACARE / @yield('breadcrumb-parent', Auth::check() ? strtoupper(Auth::user()->role) : 'PORTAL')
                    </span>
                    <h2 class="text-lg font-bold text-slate-800">
                        @yield('breadcrumb-title', 'Halaman')
                    </h2>
                </div>

                <!-- Right Action Bar -->
                <div class="flex items-center gap-3">
                    @auth
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-xs font-semibold bg-cyan-50 border border-cyan-200/80 text-cyan-700 capitalize">
                            {{ ucfirst(Auth::user()->role) }}
                        </span>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-semibold text-cyan-600 hover:underline">Masuk</a>
                        <span class="text-slate-300">|</span>
                        <a href="{{ route('register') }}" class="text-xs font-semibold text-slate-600 hover:underline">Daftar Warga</a>
                    @endauth
                </div>
            </header>

            <!-- Alerts -->
            <div class="px-8 pt-6">
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between text-sm shadow-xs">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-check text-emerald-600"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
                    </div>
                @endif

                @if(session('status'))
                    <div class="p-4 rounded-xl bg-cyan-50 border border-cyan-200 text-cyan-800 flex items-center justify-between text-sm shadow-xs">
                        <div class="flex items-center gap-2.5">
                            <i class="fa-solid fa-circle-info text-cyan-600"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                        <button onclick="this.parentElement.remove()" class="text-cyan-600 hover:text-cyan-900 font-bold">&times;</button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm shadow-xs">
                        <div class="flex items-start gap-2.5">
                            <i class="fa-solid fa-triangle-exclamation text-rose-600 mt-0.5"></i>
                            <div>
                                <span class="font-bold">Terjadi kesalahan:</span>
                                <ul class="list-disc list-inside mt-1 text-xs space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Page Content -->
            <main class="flex-1 p-8">
                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="px-8 py-4 border-t border-slate-200/80 bg-white text-xs text-slate-400 flex justify-between items-center">
                <span>&copy; 2026 AquaCare - Platform Monitoring dan Pelaporan Air Bersih (SDG 6)</span>
                <span>Modul PBI-18 & Fitur Dasar &bull; PIC: Arrival Sudrajat</span>
            </footer>
        </div>
    </div>

    @yield('scripts')
</body>
</html>
