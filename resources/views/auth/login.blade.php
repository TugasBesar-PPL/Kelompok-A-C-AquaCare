<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk ke AquaCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        aqua: {
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                        },
                        navy: {
                            900: '#0f172a',
                            950: '#090d16',
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
        
        <!-- Header -->
        <div class="bg-navy-900 px-8 py-7 text-center text-white relative">
            <div class="w-12 h-12 rounded-xl bg-cyan-500/20 text-cyan-400 mx-auto flex items-center justify-center font-bold text-2xl mb-3">
                <i class="fa-solid fa-droplet"></i>
            </div>
            <h1 class="text-2xl font-black tracking-wide">AquaCare</h1>
            <p class="text-xs text-slate-300 mt-1">Platform Monitoring & Pelaporan Air Bersih</p>
        </div>

        <div class="p-8 space-y-6">

            <!-- Flash Error -->
            @if($errors->any())
            <div class="p-3 bg-rose-50 border border-rose-200 rounded-lg text-rose-700 text-xs">
                @foreach($errors->all() as $err)
                    <p class="flex items-center gap-1.5"><i class="fa-solid fa-circle-exclamation"></i> {{ $err }}</p>
                @endforeach
            </div>
            @endif

            @if(session('success'))
            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-800 text-xs">
                <p class="flex items-center gap-1.5"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Email Akun</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="email" name="email" value="{{ old('email', 'admin@aquacare.id') }}" required 
                               class="w-full pl-9 pr-3.5 py-2.5 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500" 
                               placeholder="nama@aquacare.id">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="password" name="password" value="password123" required 
                               class="w-full pl-9 pr-3.5 py-2.5 text-xs rounded-lg border border-slate-300 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500" 
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="rounded text-cyan-600 focus:ring-cyan-500" checked>
                        <span>Ingat saya (sesi 2 jam)</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-2.5 bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs rounded-lg shadow-sm transition-colors flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Masuk ke Sistem</span>
                </button>
            </form>

            <!-- Quick Demo Login Helpers -->
            <div class="pt-4 border-t border-slate-200">
                <span class="block text-center text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-3">
                    Akses Cepat Pengujian (Evaluasi)
                </span>
                <div class="grid grid-cols-3 gap-2">
                    <a href="{{ route('quick-login', 'admin') }}" class="py-2 px-2 text-center rounded-lg bg-navy-900 text-cyan-300 hover:bg-slate-800 text-xs font-bold transition-colors">
                        <i class="fa-solid fa-user-shield block mb-0.5"></i> Admin (Fathir)
                    </a>
                    <a href="{{ route('quick-login', 'petugas') }}" class="py-2 px-2 text-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                        <i class="fa-solid fa-user-gear block mb-0.5 text-cyan-600"></i> Petugas
                    </a>
                    <a href="{{ route('quick-login', 'warga') }}" class="py-2 px-2 text-center rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-colors">
                        <i class="fa-solid fa-user block mb-0.5 text-slate-500"></i> Warga
                    </a>
                </div>
            </div>

        </div>

    </div>

</body>
</html>

