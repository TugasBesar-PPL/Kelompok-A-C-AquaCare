<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — AquaCare</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }
    </style>
</head>
<body class="bg-[#f3f4f6] min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="inline-flex items-center gap-2.5">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-[#1a3a5c]">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3c-4.97 0-9 4.03-9 9s4.03 9 9 9c1.66 0 3.22-.45 4.56-1.24M12 3v9l6.36 3.64" />
                    </svg>
                </div>
                <span class="text-2xl font-bold text-[#1a3a5c]">AquaCare</span>
            </div>
            <p class="mt-2 text-sm text-gray-500">Platform Monitoring dan Pelaporan Air Bersih</p>
        </div>

        {{-- Login Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-800 mb-1">Masuk ke akun Anda</h2>
            <p class="text-sm text-gray-500 mb-6">Masukkan email dan password untuk melanjutkan.</p>

            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-600">{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1a3a5c]/20 focus:border-[#1a3a5c] transition"
                           placeholder="email@aquacare.id">
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#1a3a5c]/20 focus:border-[#1a3a5c] transition"
                           placeholder="••••••••">
                </div>

                <button type="submit"
                        class="w-full px-4 py-2.5 bg-[#1a3a5c] hover:bg-[#152e49] text-white text-sm font-medium rounded-lg transition-colors">
                    Masuk
                </button>
            </form>
        </div>

        {{-- Demo accounts info --}}
        <div class="mt-4 bg-white rounded-xl shadow-sm border border-gray-200 p-4">
            <p class="text-xs font-medium text-gray-500 mb-2">Akun Demo:</p>
            <div class="space-y-1 text-xs text-gray-500">
                <p><span class="font-medium text-gray-700">Admin:</span> admin@aquacare.id / password</p>
                <p><span class="font-medium text-gray-700">Petugas:</span> petugas@aquacare.id / password</p>
                <p><span class="font-medium text-gray-700">Warga:</span> warga@aquacare.id / password</p>
            </div>
        </div>
    </div>
</body>
</html>
