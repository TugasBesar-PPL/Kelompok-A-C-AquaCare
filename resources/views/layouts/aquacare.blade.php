<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AquaCare — Monitoring Kualitas Air')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1"></script>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
<div class="min-h-screen flex">
    <!-- Sidebar sesuai Mockup Gambar 7 & 8 -->
    <aside class="w-64 bg-[#0f3443] text-white flex-shrink-0 hidden md:flex flex-col">
        <div class="p-6 flex items-center gap-3 border-b border-white/10">
            <div class="w-8 h-8 rounded-full bg-teal-400/20 flex items-center justify-center text-teal-300 font-bold">💧</div>
            <span class="text-xl font-bold tracking-tight">AquaCare</span>
        </div>
        <nav class="p-4 space-y-1 text-sm">
            <a href="{{ route('monitoring.index') }}"
               class="block px-4 py-2.5 rounded-lg {{ request()->routeIs('monitoring.index', 'monitoring.show') ? 'bg-teal-600 font-semibold text-white' : 'text-slate-200 hover:bg-white/10' }}">
                Titik Sumber Air & Tren
            </a>
            <a href="{{ route('monitoring.create') }}"
               class="block px-4 py-2.5 rounded-lg {{ request()->routeIs('monitoring.create') ? 'bg-teal-600 font-semibold text-white' : 'text-slate-200 hover:bg-white/10' }}">
                Input Pengukuran (PBI-03)
            </a>
        </nav>
    </aside>

    <!-- Konten Utama -->
    <main class="flex-1 flex flex-col">
        <header class="bg-white border-b border-slate-200 px-6 py-4 flex items-center justify-between">
            <div class="text-sm text-slate-500">
                @yield('breadcrumb')
            </div>
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-[#0f3443] text-white text-xs font-bold flex items-center justify-center">
                    KA
                </div>
                <span class="text-sm font-medium text-slate-700">Krisna Arenko</span>
            </div>
        </header>

        <div class="p-6 max-w-7xl w-full mx-auto">
            @if (session('success'))
                <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 rounded-lg bg-rose-50 border border-rose-200 px-4 py-3 text-sm text-rose-800">
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</div>
</body>
</html>