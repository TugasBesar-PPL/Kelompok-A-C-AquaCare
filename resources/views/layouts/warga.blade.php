<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AquaCare')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>

    @yield('styles')
</head>
<body class="bg-slate-50 text-slate-800">
    <div class="min-h-screen flex">
        <aside class="hidden md:flex w-64 flex-col bg-slate-900 text-white">
            <div class="px-6 py-7 border-b border-slate-700">
                <h1 class="text-2xl font-extrabold tracking-tight">
                    Aqua<span class="text-cyan-400">Care</span>
                </h1>
                <p class="mt-1 text-xs text-slate-400">Layanan Laporan Air</p>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                <a href="{{ route('reports.index') }}"
                   class="block rounded-xl px-4 py-3 text-sm font-semibold transition
                   {{ request()->routeIs('reports.index', 'reports.show') ? 'bg-cyan-500 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                    Daftar Laporan Saya
                </a>

                <a href="{{ route('reports.create') }}"
                   class="block rounded-xl px-4 py-3 text-sm font-semibold transition
                   {{ request()->routeIs('reports.create') ? 'bg-cyan-500 text-white' : 'text-slate-300 hover:bg-slate-800' }}">
                    Ajukan Laporan
                </a>
            </nav>

            <div class="border-t border-slate-700 px-6 py-5">
                <p class="text-xs text-slate-400">Area Warga</p>
                <p class="mt-1 text-sm font-semibold">AquaCare</p>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="border-b border-slate-200 bg-white px-6 py-5 md:px-8">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-cyan-600">
                            AquaCare / Warga
                        </p>
                        <h2 class="mt-1 text-xl font-bold text-slate-900">
                            @yield('breadcrumb', 'Area Warga')
                        </h2>
                    </div>

                    <div class="rounded-full bg-cyan-50 px-4 py-2 text-sm font-semibold text-cyan-700">
                        Warga
                    </div>
                </div>

                <nav class="mt-4 flex gap-2 md:hidden">
                    <a href="{{ route('reports.index') }}"
                       class="rounded-lg bg-slate-100 px-3 py-2 text-sm font-medium">
                        Laporan Saya
                    </a>
                    <a href="{{ route('reports.create') }}"
                       class="rounded-lg bg-cyan-500 px-3 py-2 text-sm font-medium text-white">
                        Ajukan Laporan
                    </a>
                </nav>
            </header>

            <main class="flex-1 px-4 py-6 md:px-8 md:py-8">
                @yield('content')
            </main>

            <footer class="border-t border-slate-200 bg-white px-6 py-4 text-center text-xs text-slate-500">
                © {{ date('Y') }} AquaCare — Layanan Pelaporan Masalah Air
            </footer>
        </div>
    </div>

    @yield('scripts')
</body>
</html>