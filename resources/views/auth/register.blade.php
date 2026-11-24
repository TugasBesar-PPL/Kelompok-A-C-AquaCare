<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Akun Warga - AquaCare</title>
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
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h1 class="text-2xl font-black tracking-tight">Daftar Akun Warga</h1>
            <p class="text-xs text-slate-300 mt-1 font-medium">Buat akun untuk mengajukan & memantau laporan air bersih</p>
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

            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <i class="fa-solid fa-user absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="name" value="{{ old('name') }}" required 
                               class="w-full pl-10 pr-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 placeholder-slate-400 bg-white" 
                               placeholder="Contoh: Siti Rahma">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alamat Email</label>
                    <div class="relative">
                        <i class="fa-solid fa-envelope absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="email" name="email" value="{{ old('email') }}" required 
                               class="w-full pl-10 pr-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 placeholder-slate-400 bg-white" 
                               placeholder="nama@gmail.com">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">No. HP / WhatsApp (Opsional)</label>
                    <div class="relative">
                        <i class="fa-solid fa-phone absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="text" name="phone" value="{{ old('phone') }}" 
                               class="w-full pl-10 pr-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 placeholder-slate-400 bg-white" 
                               placeholder="08123456789">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Password (Minimal 8 Karakter)</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="password" name="password" required 
                               class="w-full pl-10 pr-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 placeholder-slate-400 bg-white" 
                               placeholder="••••••••">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Ulangi Password</label>
                    <div class="relative">
                        <i class="fa-solid fa-lock-open absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                        <input type="password" name="password_confirmation" required 
                               class="w-full pl-10 pr-4 py-3 text-xs rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500 placeholder-slate-400 bg-white" 
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs rounded-xl shadow-md shadow-cyan-600/20 transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>Daftar Sebagai Warga (FD-02)</span>
                    </button>
                </div>
            </form>

            <!-- Login Link -->
            <div class="text-center pt-2">
                <p class="text-xs text-slate-500">
                    Sudah memiliki akun? 
                    <a href="{{ route('login') }}" class="font-bold text-cyan-600 hover:underline">
                        Masuk ke Sistem (FD-01)
                    </a>
                </p>
            </div>

        </div>

    </div>

</body>
</html>
