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
                        cyan: {
                            500: '#06b6d4',
                            600: '#0891b2',
                            700: '#0e7490',
                        },
                        navy: {
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
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-slate-200/80 overflow-hidden">
        
        <!-- Header -->
        <div class="bg-navy-950 px-8 py-8 text-center text-white relative">
            <div class="w-14 h-14 rounded-2xl bg-cyan-500/20 text-cyan-400 mx-auto flex items-center justify-center font-bold text-2xl mb-3 shadow-inner">
                <i class="fa-solid fa-droplet"></i>
            </div>
            <h1 class="text-2xl font-black tracking-tight">AquaCare</h1>
            <p class="text-xs text-slate-300 mt-1 font-medium">Platform Monitoring & Pelaporan Air Bersih</p>
        </div>

        <div class="p-8 space-y-6">

            <!-- Flash Error -->
            @if($errors->any())
            <div class="p-3.5 bg-rose-50 border border-rose-200 rounded-xl text-rose-700 text-xs">
                @foreach($errors->all() as $err)
                    <p class="flex items-center gap-2"><i class="fa-solid fa-circle-exclamation text-rose-500"></i> {{ $err }}</p>
                @endforeach
            </div>
            @endif

            @if(session('success'))
            <div class="p-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-800 text-xs">
                <p class="flex items-center gap-2"><i class="fa-solid fa-circle-check text-emerald-600"></i> {{ session('success') }}</p>
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Email Akun</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="email" name="email" value="{{ old('email', 'admin@aquacare.id') }}" required 
                               class="w-full pl-10 pr-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 placeholder-slate-400 bg-white" 
                               placeholder="nama@aquacare.id">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="password" name="password" value="password123" required 
                               class="w-full pl-10 pr-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 placeholder-slate-400 bg-white" 
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center gap-2 cursor-pointer text-slate-600">
                        <input type="checkbox" name="remember" class="rounded text-cyan-600 focus:ring-cyan-500" checked>
                        <span>Ingat saya (sesi 2 jam)</span>
                    </label>
                    <span class="text-[11px] text-slate-400">FD-01 Auth</span>
                </div>

                <button type="submit" class="w-full py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs rounded-xl shadow-md shadow-cyan-600/20 transition-all flex items-center justify-center gap-2">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span>Masuk ke Sistem</span>
                </button>
            </form>

            <!-- Register Link for Warga -->
            <div class="text-center pt-2">
                <p class="text-xs text-slate-500">
                    Belum punya akun warga? 
                    <a href="{{ route('register') }}" class="font-bold text-cyan-600 hover:underline">
                        Daftar sekarang (FD-02)
                    </a>
                </p>
            </div>

            <!-- Quick Demo Login Helpers -->
            <div class="pt-5 border-t border-slate-200/80">
                <span class="block text-center text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-3">
                    Akses Cepat Pengujian (Evaluasi Tim)
                </span>
                <div class="grid grid-cols-3 gap-2">
                    <a href="{{ route('quick-login', 'admin') }}" class="py-2.5 px-2 text-center rounded-xl bg-navy-950 text-cyan-300 hover:bg-slate-800 text-xs font-bold transition-all shadow-xs">
                        <i class="fa-solid fa-user-shield block mb-1"></i> Admin
                    </a>
                    <a href="{{ route('quick-login', 'petugas') }}" class="py-2.5 px-2 text-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-all">
                        <i class="fa-solid fa-user-gear block mb-1 text-cyan-600"></i> Petugas
                    </a>
                    <a href="{{ route('quick-login', 'warga') }}" class="py-2.5 px-2 text-center rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold transition-all">
                        <i class="fa-solid fa-user block mb-1 text-slate-500"></i> Warga
                    </a>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
