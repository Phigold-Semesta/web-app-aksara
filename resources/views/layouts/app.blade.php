<!DOCTYPE html>
<html lang="id" x-data="{ 
    darkMode: localStorage.getItem('theme') === 'dark',
    toggleTheme() {
        this.darkMode = !this.darkMode;
        localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
    }
}" :class="{ 'dark': darkMode }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | AKSARA LPSE Karawang</title>
    
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'aksara-emerald': '#008f5d',
                        'aksara-gold': '#b45309',
                    },
                    screens: {
                        'xs': '475px',
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; transition: background-color 0.3s ease; overflow-x: hidden; }
        .sidebar-active { background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.2); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
        
        /* Sidebar Transitions & Responsive Width */
        #main-sidebar { width: 88px; transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
        
        @media (min-width: 1024px) {
            #main-sidebar:hover { width: 288px; }
            #main-sidebar .nav-text, #main-sidebar .logo-full, #main-sidebar .menu-header { opacity: 0; display: none; transition: opacity 0.3s ease; }
            #main-sidebar:hover .nav-text, #main-sidebar:hover .logo-full, #main-sidebar:hover .menu-header { opacity: 1; display: flex; }
            #main-sidebar .icon-collapsed { display: flex; }
            #main-sidebar:hover .icon-collapsed { display: none; }
            #main-sidebar .nav-item { justify-content: center; }
            #main-sidebar:hover .nav-item { justify-content: flex-start; }
        }

        @media (max-width: 1024px) {
            #main-sidebar { position: fixed; left: -100%; width: 280px; height: 100vh; }
            #main-sidebar.show-sidebar { left: 0; }
            #main-sidebar .nav-text, #main-sidebar .logo-full { display: flex; opacity: 1; }
            #main-sidebar .icon-collapsed { display: none; }
            #main-sidebar .menu-header { display: block; opacity: 1; }
        }
    </style>
</head>
<body class="antialiased text-slate-800 bg-[#f0f9f4] dark:bg-emerald-950 dark:text-emerald-50 transition-colors duration-300">

    <!-- Overlay for Mobile -->
    <div id="sidebar-overlay" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/40 z-30 hidden backdrop-blur-sm transition-opacity duration-300"></div>

    <div class="flex min-h-screen relative overflow-hidden">
        
        <!-- SIDEBAR AKSARA -->
        <aside id="main-sidebar" class="bg-[#008f5d] dark:bg-emerald-900 h-screen text-white flex flex-col z-40 shadow-2xl shrink-0 overflow-hidden group">
            
            <!-- Logo Section -->
            <div class="p-6 h-24 flex items-center border-b border-white/10 shrink-0">
                <div class="logo-full items-center gap-3">
                    <div class="bg-white p-2 rounded-xl shadow-lg shrink-0">
                        <i class="fas fa-file-invoice text-[#008f5d] text-lg"></i>
                    </div>
                    <span class="font-extrabold tracking-tighter text-lg uppercase leading-none text-nowrap">
                        AKSARA<br><span class="text-emerald-300 text-[10px]">LPSE Kab. Karawang</span>
                    </span>
                </div>
                <div class="icon-collapsed w-full justify-center">
                    <div class="bg-white p-3 rounded-2xl shadow-xl border-4 border-emerald-400/30">
                        <i class="fas fa-file-invoice text-[#008f5d] text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Navigation Section -->
            <nav class="flex-1 px-4 mt-6 overflow-y-auto custom-scrollbar space-y-1">
                
                <!-- DASHBOARD (Universal dengan Icon Baru) -->
                <div class="menu-header px-4 py-3 text-[10px] font-black text-emerald-200/50 uppercase tracking-[0.2em]">Utama</div>
                <a href="{{ route(auth()->user()->role . '.dashboard') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Request::is('*/dashboard') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                    <i class="fas fa-house-chimney w-6 text-center text-sm"></i>
                    <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">
                        {{ in_array(auth()->user()->role, ['admin', 'pimpinan', 'petugas']) ? 'Dashboard & Laporan' : 'Dashboard' }}
                    </span>
                </a>

                <!-- ==========================================
                     AKTOR: ADMINISTRATOR
                =========================================== -->
                @if(auth()->user()->role === 'admin')
                    <div class="menu-header px-4 py-3 text-[10px] font-black text-emerald-200/50 uppercase tracking-[0.2em] mt-4">Master Data</div>
                    <a href="{{ route('admin.master.user.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Request::is('admin/master/user*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-users-gear w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Mengelola Data User</span>
                    </a>
                    <a href="{{ route('admin.master.kategori.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Request::is('admin/master/kategori*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-tags w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Mengelola Master Kategori</span>
                    </a>
                    <a href="{{ route('admin.master.instruksi.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Request::is('admin/master/instruksi*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-list-check w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Master Instruksi Pimpinan</span>
                    </a>

                    <div class="menu-header px-4 py-3 text-[10px] font-black text-emerald-200/50 uppercase tracking-[0.2em] mt-4">Transaksi</div>
                    <a href="{{ route('admin.manajemen_surat.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Request::is('admin/manajemen_surat*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-file-import w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Input & Digitalisasi Surat</span>
                    </a>
                    <a href="{{ route('admin.manajemen_arsip.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Request::is('admin/manajemen_arsip*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-box-archive w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Manajemen Arsip</span>
                    </a>

                    <div class="menu-header px-4 py-3 text-[10px] font-black text-emerald-200/50 uppercase tracking-[0.2em] mt-4">Sistem</div>
                    <a href="{{ route('admin.aktivitas.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Request::is('admin/aktivitas*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-clock-rotate-left w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Monitoring Audit Log</span>
                    </a>
                @endif

                <!-- ==========================================
                     AKTOR: PETUGAS
                =========================================== -->
                @if(auth()->user()->role === 'petugas')
                    <div class="menu-header px-4 py-3 text-[10px] font-black text-emerald-200/50 uppercase tracking-[0.2em] mt-4">Transaksi</div>
                    <a href="{{ route('petugas.manajemen_surat.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Request::is('petugas/manajemen_surat*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-envelope-open-text w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Input & Digitalisasi Surat</span>
                    </a>
                    <a href="{{ route('petugas.manajemen_arsip.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Request::is('petugas/manajemen_arsip*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-box-archive w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Manajemen Arsip</span>
                    </a>
                    <!-- Menu Laporan Petugas telah dihapus (digabung ke Dashboard) -->
                @endif

                <!-- ==========================================
                     AKTOR: PIMPINAN
                =========================================== -->
                @if(auth()->user()->role === 'pimpinan')
                    <div class="menu-header px-4 py-3 text-[10px] font-black text-emerald-200/50 uppercase tracking-[0.2em] mt-4">Verifikasi</div>
                    <a href="{{ route('pimpinan.instruksi_surat.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Request::is('pimpinan/instruksi_surat*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-file-signature w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Menerima & Meninjau Surat</span>
                    </a>

                    <div class="menu-header px-4 py-3 text-[10px] font-black text-emerald-200/50 uppercase tracking-[0.2em] mt-4">Monitoring</div>
                    <a href="{{ route('pimpinan.monitoring_riwayat.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Request::is('pimpinan/monitoring_riwayat*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-shoe-prints w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Monitoring Riwayat Surat</span>
                    </a>
                    <a href="{{ route('pimpinan.monitoring_arsip.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Request::is('pimpinan/monitoring_arsip*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-file-shield w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Monitoring Arsip Surat</span>
                    </a>

                    <div class="menu-header px-4 py-3 text-[10px] font-black text-emerald-200/50 uppercase tracking-[0.2em] mt-4">Sistem</div>
                    <a href="{{ route('admin.aktivitas.index') }}" class="nav-item flex items-center py-4 px-5 rounded-2xl transition-all {{ Request::is('admin/aktivitas*') ? 'sidebar-active' : 'hover:bg-white/10' }}">
                        <i class="fas fa-clock-rotate-left w-6 text-center text-sm"></i>
                        <span class="nav-text ml-3 text-sm font-bold tracking-wide text-nowrap">Monitoring Audit Log</span>
                    </a>
                @endif
            </nav>

            <!-- Logout Section -->
            <div class="p-4 mb-4">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                <button onclick="confirmLogout(event)" class="w-full flex items-center justify-center py-4 px-6 rounded-2xl bg-white/5 hover:bg-red-500 text-red-100 transition-all border border-white/5 group">
                    <i class="fas fa-power-off w-6 text-center group-hover:scale-110"></i>
                    <span class="nav-text ml-3 text-sm font-bold tracking-widest uppercase text-nowrap">Keluar</span>
                </button>
            </div>
        </aside>

        <!-- MAIN CONTENT (Responsive Flex) -->
        <main class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden relative">
            <!-- Header (Responsive Header) -->
            <header class="h-20 bg-white dark:bg-emerald-900 border-b border-emerald-50 dark:border-emerald-800 shadow-sm flex justify-between items-center px-4 sm:px-8 z-20 shrink-0 transition-colors">
                <div class="flex items-center gap-3 sm:gap-4">
                    <!-- Hamburger Mobile -->
                    <button onclick="toggleMobileSidebar()" class="lg:hidden w-10 h-10 flex items-center justify-center rounded-xl bg-emerald-50 text-[#008f5d] dark:bg-emerald-800 dark:text-emerald-100">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="flex flex-col leading-none">
                        <h2 class="text-slate-800 dark:text-white font-black text-lg sm:text-xl tracking-tighter uppercase italic truncate max-w-[150px] sm:max-w-none">@yield('title', 'Dashboard')</h2>
                        <p class="text-[9px] font-bold text-slate-400 dark:text-emerald-400/60 uppercase tracking-[0.25em] mt-1.5 hidden xs:block">Aksara • Tata Kelola Arsip</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <!-- Theme Toggle -->
                    <button @click="toggleTheme()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-emerald-800 text-slate-500 dark:text-yellow-400 border border-slate-100 dark:border-emerald-700 transition-all">
                        <i x-show="darkMode" class="fa-solid fa-sun text-lg" x-cloak></i>
                        <i x-show="!darkMode" class="fa-solid fa-moon text-lg" x-cloak></i>
                    </button>

                    <!-- User Profile -->
                    <div class="flex items-center gap-2 sm:gap-4 bg-slate-50 dark:bg-emerald-800/50 py-1.5 pl-2 sm:pl-4 pr-1.5 rounded-2xl border border-slate-100 dark:border-emerald-700">
                        <div class="text-right leading-tight hidden md:block">
                            <p class="text-xs font-black text-slate-800 dark:text-emerald-50 uppercase tracking-tighter">{{ Auth::user()->username }}</p>
                            <div class="flex items-center justify-end gap-1.5 mt-0.5">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                <p class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest italic">{{ strtoupper(Auth::user()->role) }}</p>
                            </div>
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->username }}&background=008f5d&color=fff&bold=true" class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl shadow-sm border-2 border-white dark:border-emerald-700 shrink-0">
                    </div>
                </div>
            </header>

            <!-- Page Content (Responsive Content Area) -->
            <div class="flex-1 overflow-y-auto p-4 sm:p-8 custom-scrollbar bg-[#f8fafc] dark:bg-emerald-950/50">
                <div class="max-w-7xl mx-auto">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('show-sidebar');
            
            if(sidebar.classList.contains('show-sidebar')) {
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                overlay.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        function confirmLogout(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Akhiri Sesi?',
                text: "Anda harus login kembali untuk mengakses sistem.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#008f5d',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'YA, KELUAR',
                cancelButtonText: 'BATAL',
                reverseButtons: true,
                background: document.documentElement.classList.contains('dark') ? '#064e3b' : '#fff',
                color: document.documentElement.classList.contains('dark') ? '#ecfdf5' : '#1e293b',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }
    </script>
</body>
</html>