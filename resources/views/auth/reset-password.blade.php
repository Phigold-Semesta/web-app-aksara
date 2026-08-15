<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - AKSARA LPSE Karawang</title>
    {{-- Tailwind CSS & FontAwesome --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body class="bg-gradient-to-br from-emerald-950 via-emerald-900 to-emerald-950 min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-white dark:bg-emerald-900/40 p-8 rounded-[2rem] shadow-2xl border border-emerald-50 dark:border-emerald-800/50 backdrop-blur-xl animate__animated animate__fadeIn">
        
        {{-- Header Logo & Title --}}
        <div class="text-center mb-8">
            <div class="inline-block bg-emerald-900 text-white px-6 py-2 rounded-2xl font-black tracking-widest text-lg shadow-lg mb-3">
                AKSARA <span class="text-emerald-400">LPSE</span>
            </div>
            <h1 class="text-2xl font-black text-emerald-950 dark:text-white uppercase italic">Buat Password Baru</h1>
            <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 mt-1 uppercase tracking-widest">Sistem Otomasi Disposisi dan Arsip</p>
        </div>

        {{-- Alert Error --}}
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-xl text-xs font-bold">
                <ul class="space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Reset Password --}}
        <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
            @csrf
            
            {{-- Token & Email Hidden --}}
            <input type="hidden" name="token" value="{{ $token }}">
            
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-2 ml-1">Email Terdaftar</label>
                <input type="email" name="email" value="{{ $email ?? old('email') }}" required readonly
                    class="w-full bg-emerald-50/50 dark:bg-emerald-950/50 border-2 border-transparent rounded-xl px-4 py-3.5 text-sm font-semibold text-emerald-900 dark:text-white outline-none cursor-not-allowed">
            </div>

            {{-- Password Baru dengan Toggle Lihat/Tutup --}}
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-2 ml-1">Password Baru</label>
                <div class="relative">
                    <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter"
                        class="w-full bg-emerald-50 dark:bg-emerald-950 border-2 border-transparent focus:border-emerald-500 dark:border-emerald-800 rounded-xl px-4 py-3.5 text-sm text-emerald-900 dark:text-white outline-none transition-all pr-12">
                    <button type="button" onclick="togglePassword('password', 'icon-password')" class="absolute right-4 top-1/2 -translate-y-1/2 text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-200 focus:outline-none transition-all">
                        <i id="icon-password" class="fas fa-eye-slash"></i>
                    </button>
                </div>
            </div>

            {{-- Konfirmasi Password Baru dengan Toggle Lihat/Tutup --}}
            <div>
                <label class="block text-[10px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-2 ml-1">Konfirmasi Password Baru</label>
                <div class="relative">
                    <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password baru"
                        class="w-full bg-emerald-50 dark:bg-emerald-950 border-2 border-transparent focus:border-emerald-500 dark:border-emerald-800 rounded-xl px-4 py-3.5 text-sm text-emerald-900 dark:text-white outline-none transition-all pr-12">
                    <button type="button" onclick="togglePassword('password_confirmation', 'icon-password-conf')" class="absolute right-4 top-1/2 -translate-y-1/2 text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-200 focus:outline-none transition-all">
                        <i id="icon-password-conf" class="fas fa-eye-slash"></i>
                    </button>
                </div>
            </div>

            {{-- Tombol Submit --}}
            <div>
                <button type="submit" class="w-full bg-emerald-900 dark:bg-emerald-600 text-white py-4 rounded-xl font-black uppercase text-xs hover:bg-emerald-800 dark:hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-900/20 tracking-wider">
                    Perbarui Password
                </button>
            </div>
        </form>
    </div>

    {{-- Skrip JavaScript Interaktif untuk Tombol Lihat/Tutup Password --}}
    <script>
        function togglePassword(fieldId, iconId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }
    </script>
</body>
</html>