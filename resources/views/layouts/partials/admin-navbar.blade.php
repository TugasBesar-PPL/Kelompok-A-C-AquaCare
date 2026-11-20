{{-- Navbar Admin AquaCare --}}
<header class="bg-white border-b border-gray-200 sticky top-0 z-20">
    <div class="flex items-center justify-between h-14 px-4 lg:px-6">
        {{-- Left: Mobile menu button + Breadcrumb --}}
        <div class="flex items-center gap-3">
            {{-- Mobile hamburger --}}
            <button onclick="toggleSidebar()" class="lg:hidden p-1.5 rounded-lg hover:bg-gray-100 text-gray-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Breadcrumb --}}
            <nav class="text-sm text-gray-500">
                @yield('breadcrumb')
            </nav>
        </div>

        {{-- Right: User info --}}
        <div class="flex items-center gap-3">
            {{-- User avatar + name --}}
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-[#1a3a5c] flex items-center justify-center text-white text-xs font-semibold">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                </div>
                <span class="hidden sm:block text-sm font-medium text-gray-700">{{ auth()->user()->name ?? 'Admin' }}</span>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="p-1.5 rounded-lg hover:bg-gray-100 text-gray-400 hover:text-gray-600" title="Logout">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</header>
