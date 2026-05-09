<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | AKSARA LPSE Karawang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            margin: 0;
            /* Latar belakang Batik Mega Mendung */
            background: url("{{ asset('img/batik_emerald_green.png') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
        }

        /* Overlay yang membuat batik lebih 'BOLD' dan berkarakter */
        .luxury-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Menggunakan mix-blend-mode multiply agar corak batik lebih tajam/bold */
            background: radial-gradient(circle at center, rgba(6, 78, 59, 0.15) 0%, rgba(2, 44, 34, 0.85) 100%);
            backdrop-filter: contrast(1.1) brightness(0.9);
            z-index: 1;
        }

        /* Animasi cahaya lembut */
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

        .reveal-text {
            display: inline-block;
            animation: tracking-in-expand 0.8s cubic-bezier(0.215, 0.610, 0.355, 1.000) both;
        }

        @keyframes tracking-in-expand {
            0% { letter-spacing: -0.2em; opacity: 0; }
            40% { opacity: 0.6; }
            100% { opacity: 1; }
        }

        /* Glassmorphism Mewah & Modern */
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
    <div class="light-glow bottom-0 right-0" style="animation-delay: -5s;"></div>

    <div class="w-full max-w-md relative z-10">
        <div class="text-center mb-10">
            <div class="flex items-center justify-center mb-5">
                <div class="flex items-center bg-emerald-950/80 backdrop-blur-3xl px-9 py-3.5 rounded-3xl border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.4)]">
                    <h1 class="text-white text-3xl font-black tracking-tighter uppercase reveal-text">
                        AKSARA <span class="text-emerald-400 font-bold ml-1">LPSE</span>
                    </h1>
                    <div class="ml-4 text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V21a2 2 0 01-2 2H10a2 2 0 01-2-2v-2" />
                        </svg>
                    </div>
                </div>
            </div>
            <p class="text-emerald-50 text-sm font-bold tracking-[0.1em] uppercase italic drop-shadow-lg opacity-90">
                "Digitalisasi Surat & Arsip LPSE Kabupaten Karawang"
            </p>
        </div>

        <div class="glass-card p-10 rounded-[3rem] relative overflow-hidden">
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-emerald-400/10 blur-3xl"></div>

            <div class="relative z-10">
                <div class="flex flex-col items-center justify-center text-center">
                    <h2 class="text-white text-2xl font-extrabold mb-1 tracking-tight">Selamat Datang</h2>
                    <p class="text-emerald-100/50 text-[10px] mb-8 tracking-[0.3em] uppercase font-black">Portal Otentikasi</p>
                </div>

                @if (session('loginError'))
                    <div class="mb-6 p-4 rounded-2xl bg-red-500/20 border border-red-500/30 text-white text-xs text-center animate-pulse">
                        <p>{{ session('loginError') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-2xl bg-red-500/20 border border-red-500/30 text-white text-xs text-center animate-pulse">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form action="{{ url('/login') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="group">
                        <label for="username" class="block text-emerald-200 text-[10px] uppercase font-black tracking-[0.3em] mb-2.5 ml-6">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" 
                            class="w-full px-8 py-4 bg-white/10 border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white/15 transition-all duration-300 text-white text-sm placeholder-white/20"
                            placeholder="Masukkan Username" required autofocus>
                    </div>

                    <div class="group">
                        <label for="password" class="block text-emerald-200 text-[10px] uppercase font-black tracking-[0.3em] mb-2.5 ml-6">Password</label>
                        <input type="password" name="password" id="password" 
                            class="w-full px-8 py-4 bg-white/10 border border-white/10 rounded-full focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:bg-white/15 transition-all duration-300 text-white text-sm placeholder-white/20"
                            placeholder="••••••••" required>
                    </div>

                    <button type="submit" 
                        class="w-full bg-emerald-400 hover:bg-emerald-300 text-emerald-950 font-black py-4 rounded-full shadow-[0_15px_40px_rgba(52,211,153,0.3)] transform hover:scale-[1.03] active:scale-[0.97] transition-all duration-500 mt-8 text-xs tracking-[0.2em] uppercase">
                        MASUK KE SISTEM
                    </button>
                </form>
            </div>
        </div>

        <div class="text-center mt-12 space-y-3">
            <div class="h-[1px] w-16 bg-white/20 mx-auto"></div>
            <p class="text-emerald-100/30 text-[9px] font-bold tracking-[0.5em] uppercase">
                LPSE Kabupaten Karawang
            </p>
        </div>
    </div>

</body>
</html>