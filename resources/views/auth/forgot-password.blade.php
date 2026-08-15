<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password | AKSARA LPSE Karawang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            margin: 0;
            background: url("{{ asset('img/batik_emerald_green.png') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }
        .luxury-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(6, 78, 59, 0.15) 0%, rgba(2, 44, 34, 0.85) 100%);
            backdrop-filter: contrast(1.1) brightness(0.9);
            z-index: 1;
        }
        .light-glow {
            position: absolute;
            width: 700px;
            height: 700px;
            background: rgba(52, 211, 153, 0.12);
            filter: blur(130px);
            border-radius: 50%;
            z-index: 2;
            animation: move 20s infinite alternate ease-in-out;
        }
        @keyframes move {
            from { transform: translate(-20%, -20%); }
            to { transform: translate(25%, 25%); }
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(35px);
            -webkit-backdrop-filter: blur(35px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        }
    </style>
</head>
<body class="p-6">

    <div class="luxury-overlay"></div>
    <div class="light-glow top-0 left-0"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="text-center mb-10">
            <h1 class="text-white text-3xl font-black tracking-tighter uppercase">
                AKSARA <span class="text-emerald-400">LPSE</span>
            </h1>
            <p class="text-emerald-50 text-sm font-bold tracking-[0.1em] uppercase mt-2 opacity-90">
                Pemulihan Akses Akun
            </p>
        </div>

        <div class="glass-card p-10 rounded-[3rem] relative overflow-hidden">
            <div class="relative z-10">
                <div class="text-center mb-8">
                    <p class="text-emerald-100/70 text-xs font-semibold leading-relaxed">
                        Masukkan <span class="text-white font-bold">Username</span> atau <span class="text-white font-bold">Email</span> Anda. Sistem akan mengirimkan tautan untuk membuat kata sandi baru.
                    </p>
                </div>

                @if (session('success'))
                    <div class="mb-6 p-4 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 text-white text-xs text-center">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-red-500/20 border border-red-500/30 text-white text-xs text-center animate-pulse">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('password.email') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="group">
                        <label for="identifier" class="block text-emerald-200 text-[10px] uppercase font-black tracking-[0.3em] mb-2.5 ml-6">Username / Email</label>
                        <input type="text" name="identifier" id="identifier" value="{{ old('identifier') }}" 
                            class="w-full px-8 py-4 bg-white/10 border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white/15 transition-all duration-300 text-white text-sm placeholder-white/20"
                            placeholder="Masukkan Username atau Email" required autofocus>
                    </div>

                    <button type="submit" 
                        class="w-full bg-emerald-400 hover:bg-emerald-300 text-emerald-950 font-black py-4 rounded-full shadow-[0_15px_40px_rgba(52,211,153,0.3)] transform hover:scale-[1.03] active:scale-[0.97] transition-all duration-500 mt-8 text-xs tracking-[0.2em] uppercase">
                        Kirim Tautan Pemulihan
                    </button>
                </form>

                <div class="text-center mt-6">
                    <a href="{{ route('login') }}" class="text-[10px] text-emerald-300 hover:text-white font-bold tracking-[0.2em] uppercase transition-colors duration-300">
                        &larr; Kembali ke Login
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>