<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AquaCare - Platform Monitoring & Pelaporan Air Bersih</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
        .custom-popup .leaflet-popup-content-wrapper {
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            padding: 4px;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col font-sans">
    <!-- Navbar -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 rounded-lg bg-blue-600 flex items-center justify-center text-white font-bold text-xl shadow-md shadow-blue-500/30">
                        💧
                    </div>
                    <a href="/" class="text-xl font-bold bg-gradient-to-r from-blue-600 to-cyan-500 bg-clip-text text-transparent">AquaCare</a>
                </div>
                <!-- Menu Utama (Sprint 1: PBI-01 & PBI-12) -->
                <div class="hidden md:flex items-center space-x-6 text-sm font-medium">
                    <a href="/edukasi" class="text-slate-600 hover:text-blue-600 transition flex items-center gap-1">
                        📚 Edukasi Warga (PBI-12)
                    </a>
                    <span class="text-slate-300">|</span>
                    <a href="/admin/water-points" class="text-slate-600 hover:text-blue-600 transition flex items-center gap-1">
                        ⚙️ Kelola Titik Air (PBI-01)
                    </a>
                    <a href="/admin/announcements" class="text-slate-600 hover:text-blue-600 transition flex items-center gap-1">
                        📢 Kelola Konten (PBI-12)
                    </a>
                </div>
                <!-- User Profile dummy -->
                <div class="flex items-center space-x-3">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                        Admin / Tim C
                    </span>
                    <span class="text-sm font-semibold text-slate-700">Fadhlan</span>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-4 p-4 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between">
                <span>✅ {{ session('success') }}</span>
                <button onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 font-bold">&times;</button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 rounded-lg bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between">
                <span>⚠️ {{ session('error') }}</span>
                <button onclick="this.parentElement.remove()" class="text-rose-600 hover:text-rose-900 font-bold">&times;</button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 text-center text-xs text-slate-500">
        &copy; 2026 AquaCare - Platform Monitoring dan Pelaporan Air Bersih (SDG 6). Tim Pengembang Tubes PPL.
    </footer>
    @stack('scripts')
</body>
</html>

