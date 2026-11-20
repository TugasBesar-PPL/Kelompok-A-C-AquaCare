<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin') — AquaCare</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ac-primary: #1a3a5c;
            --ac-primary-dark: #152e49;
            --ac-primary-light: #2a5a8c;
            --ac-sidebar-bg: #1a3a5c;
            --ac-sidebar-active: #2a5a8c;
            --ac-sidebar-hover: rgba(255, 255, 255, 0.08);
            --ac-teal: #0d9488;
            --ac-teal-light: #14b8a6;
            --ac-bg: #f3f4f6;
            --ac-card: #ffffff;
        }
        body {
            font-family: 'Inter', ui-sans-serif, system-ui, sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-[#f3f4f6] min-h-screen">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        @include('layouts.partials.admin-sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen lg:ml-60">
            <!-- Navbar -->
            @include('layouts.partials.admin-navbar')

            <!-- Page Content -->
            <main class="flex-1 p-4 lg:p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Mobile sidebar overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden" onclick="toggleSidebar()"></div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
    </script>
    @stack('scripts')
</body>
</html>
