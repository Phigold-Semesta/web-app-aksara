@extends('layouts.app')

@section('content')
<div class="p-8 min-h-screen transition-colors duration-300 dark:bg-emerald-950/20">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-white tracking-tight">Manajemen Arsip Fisik</h1>
            <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Monitoring lokasi penyimpanan dan masa retensi dokumen digital (Akses Administrator)</p>
        </div>
        {{-- Tombol tambah arsip disesuaikan dengan namespace admin --}}
        <a href="{{ route('admin.manajemen_arsip.create') }}" 
           class="bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-400 text-white px-8 py-3.5 rounded-2xl font-bold transition-all shadow-lg shadow-emerald-200 dark:shadow-emerald-900/20 flex items-center gap-3 transform hover:-translate-y-1 active:scale-95 uppercase tracking-wider text-sm">
            <i class="fas fa-box-archive text-lg"></i> Catat Arsip Baru
        </a>
    </div>

    {{-- Advanced Filter & Search Section --}}
    <div class="bg-white dark:bg-emerald-900/40 rounded-[2rem] p-6 mb-8 shadow-sm border border-emerald-50 dark:border-emerald-800/50 flex flex-wrap gap-4 items-center justify-between transition-all">
        <form action="{{ route('admin.manajemen_arsip.index') }}" method="GET" class="flex flex-wrap gap-3 w-full lg:w-auto">
            
            {{-- 1. Filter Per Page / Jumlah Baris --}}
            <div class="relative">
                <select name="per_page" onchange="this.form.submit()" 
                        class="appearance-none bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-100/50 dark:border-emerald-800/50 rounded-2xl px-6 py-3 pr-10 focus:ring-2 focus:ring-emerald-500 text-emerald-900 dark:text-emerald-100 font-bold transition-all cursor-pointer shadow-sm">
                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5 Baris</option>
                    <option value="10" {{ request('per_page') == 10 || !request('per_page') ? 'selected' : '' }}>10 Baris</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua Data</option>
                </select>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-emerald-500">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </div>

            {{-- 2. Filter Status Retensi --}}
            <div class="relative">
                <select name="status" onchange="this.form.submit()" 
                    class="appearance-none bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-100/50 dark:border-emerald-800/50 rounded-2xl px-6 py-3 pr-10 focus:ring-2 focus:ring-emerald-500 text-emerald-900 dark:text-emerald-100 font-bold transition-all cursor-pointer">
                    <option value="">Semua Status</option>
                    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Inaktif" {{ request('status') == 'Inaktif' ? 'selected' : '' }}>Inaktif</option>
                </select>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-emerald-500">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </div>

            {{-- 3. Input Pencarian (Di Ujung Kanan) --}}
            <div class="relative flex-grow lg:w-80">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-emerald-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari perihal atau nomor surat..." autocomplete="off"
                    class="w-full bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-100/50 dark:border-emerald-800/50 rounded-2xl pl-12 pr-5 py-3 focus:ring-2 focus:ring-emerald-500 text-emerald-900 dark:text-emerald-100 placeholder-emerald-300 transition-all font-medium">
            </div>

            @if(request('search') || request('status') || request('per_page'))
                <a href="{{ route('admin.manajemen_arsip.index') }}" title="Reset Filter"
                   class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-5 py-3 rounded-2xl font-bold hover:bg-red-100 dark:hover:bg-red-900/40 transition-all flex items-center gap-2 border border-red-100 dark:border-red-900/30">
                    <i class="fas fa-times-circle"></i> Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto">
        <table class="w-full border-separate border-spacing-y-4">
            <thead>
                <tr class="text-emerald-900/40 dark:text-emerald-100/30 uppercase text-[11px] font-black tracking-[0.2em]">
                    <th class="px-8 py-2 text-left">Informasi Surat</th>
                    <th class="px-8 py-2 text-left">Lokasi Fisik</th>
                    <th class="px-8 py-2 text-center">Masa Retensi</th>
                    <th class="px-8 py-2 text-center">Status</th>
                    <th class="px-8 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arsips as $arsip)
                <tr class="bg-white dark:bg-emerald-900/20 hover:shadow-2xl hover:shadow-emerald-900/10 dark:hover:shadow-black/40 transition-all duration-300 group">
                    <td class="px-8 py-6 rounded-l-[2.5rem] border-y border-l border-emerald-50 dark:border-emerald-800/50">
                        <div class="flex flex-col">
                            <span class="text-emerald-950 dark:text-white font-bold text-lg mb-1 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors line-clamp-1">
                                {{ $arsip->surat?->perihal ?? 'Surat Tidak Ditemukan' }}
                            </span>
                            <div class="flex items-center gap-2 text-xs font-medium text-emerald-500/80 dark:text-emerald-400/60">
                                <span class="bg-emerald-50 dark:bg-emerald-800/40 px-2 py-0.5 rounded text-emerald-700 dark:text-emerald-300 font-bold border border-emerald-100 dark:border-emerald-700">
                                    {{ $arsip->surat?->nomor_surat ?? 'N/A' }}
                                </span>
                                <span>•</span>
                                <span class="italic">{{ $arsip->tanggal_arsip ? \Carbon\Carbon::parse($arsip->tanggal_arsip)->translatedFormat('d M Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </td>

                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-emerald-800/50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/20 flex items-center justify-center text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30">
                                <i class="fas fa-vault text-sm mr-0.5"></i>
                            </div>
                            <div class="flex flex-col">
                                <p class="text-[10px] uppercase font-black text-emerald-400 dark:text-emerald-600 leading-none mb-1">Posisi Rak</p>
                                <span class="font-bold text-emerald-800 dark:text-emerald-200">{{ $arsip->lokasi_fisik }}</span>
                            </div>
                        </div>
                    </td>

                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-emerald-800/50 text-center">
                        @if($arsip->masa_retensi)
                            <span class="font-black text-emerald-950 dark:text-white">
                                {{ \Carbon\Carbon::parse($arsip->masa_retensi)->translatedFormat('d M Y') }}
                            </span>
                            <p class="text-[10px] text-emerald-400 dark:text-emerald-500 font-bold uppercase tracking-tighter mt-1">
                                {{ \Carbon\Carbon::parse($arsip->masa_retensi)->isPast() ? 'Sudah Kadaluarsa' : 'Kadaluarsa ' . \Carbon\Carbon::parse($arsip->masa_retensi)->diffForHumans() }}
                            </p>
                        @else
                            <span class="text-gray-400 dark:text-gray-500 font-bold">N/A</span>
                        @endif
                    </td>

                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-emerald-800/50 text-center">
                        @if($arsip->status_retensi == 'Aktif')
                            <span class="bg-emerald-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full shadow-lg shadow-emerald-200 dark:shadow-none uppercase tracking-widest">Aktif</span>
                        @else
                            <span class="bg-red-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full shadow-lg shadow-red-200 dark:shadow-none uppercase tracking-widest">Inaktif</span>
                        @endif
                    </td>

                    <td class="px-8 py-6 rounded-r-[2.5rem] border-y border-r border-emerald-50 dark:border-emerald-800/50 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('admin.manajemen_arsip.show', $arsip->id_arsip) }}" 
                               class="group/btn p-2.5 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-xl hover:bg-emerald-600 dark:hover:bg-emerald-500 transition-all shadow-sm hover:scale-105" 
                               title="Lihat Detail">
                                <i class="fas fa-eye text-sm group-hover/btn:text-white transition-colors"></i>
                            </a>

                            <a href="{{ route('admin.manajemen_arsip.edit', $arsip->id_arsip) }}" 
                               class="group/btn p-2.5 bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 rounded-xl hover:bg-amber-500 dark:hover:bg-amber-500 transition-all shadow-sm hover:scale-105" 
                               title="Edit Arsip">
                                <i class="fas fa-edit text-sm group-hover/btn:text-white transition-colors"></i>
                            </a>

                            <form action="{{ route('admin.manajemen_arsip.destroy', $arsip->id_arsip) }}" method="POST" id="form-hapus-{{ $arsip->id_arsip }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="button" onclick="konfirmasiHapus('{{ $arsip->id_arsip }}')" 
                                        class="group/btn p-2.5 bg-red-50 dark:bg-red-900/40 text-red-600 dark:text-red-400 rounded-xl hover:bg-red-600 dark:hover:bg-red-500 transition-all shadow-sm hover:scale-105" 
                                        title="Hapus Arsip">
                                    <i class="fas fa-trash-alt text-sm group-hover/btn:text-white transition-colors"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center opacity-50">
                            <i class="fas fa-box-open text-6xl text-emerald-200 dark:text-emerald-800/50 mb-4"></i>
                            <p class="text-emerald-900 dark:text-emerald-100 font-bold uppercase tracking-widest text-xs">Data Arsip Tidak Ditemukan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Section Khusus dengan Scroll Horizontal Khas --}}
    @if($arsips instanceof \Illuminate\Pagination\LengthAwarePaginator && $arsips->hasPages())
    <div class="mt-8">
        <div class="bg-white dark:bg-emerald-900/30 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-emerald-800/50 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
            
            {{-- Keterangan Menampilkan Data --}}
            <div class="text-[11px] font-black uppercase tracking-wider text-emerald-600/70 dark:text-emerald-400/70">
                MENAMPILKAN {{ $arsips->firstItem() }} – {{ $arsips->lastItem() }} DARI {{ $arsips->total() }} DATA
            </div>

            {{-- Container Tombol Pagination Scrollable --}}
            <div class="overflow-x-auto max-w-full pb-2 pt-1 px-2 custom-pagination-scroll">
                <div class="flex items-center gap-2 min-w-max">
                    {{-- Link Previous --}}
                    @if ($arsips->onFirstPage())
                        <span class="px-4 py-2 bg-emerald-100/50 dark:bg-emerald-950/40 text-emerald-300 dark:text-emerald-700 font-extrabold text-xs rounded-2xl cursor-not-allowed">Prev</span>
                    @else
                        <a href="{{ $arsips->appends(request()->query())->previousPageUrl() }}" class="px-4 py-2 bg-emerald-200/60 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-200 hover:bg-emerald-300 font-extrabold text-xs rounded-2xl transition-all">Prev</a>
                    @endif

                    {{-- Elemen Angka Halaman --}}
                    @foreach ($arsips->appends(request()->query())->getUrlRange(1, $arsips->lastPage()) as $page => $url)
                        @if ($page == $arsips->currentPage())
                            <span class="w-9 h-9 flex items-center justify-center bg-emerald-900 text-white font-black text-xs rounded-2xl shadow-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center bg-emerald-200/60 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200 hover:bg-emerald-300 dark:hover:bg-emerald-800 font-bold text-xs rounded-2xl transition-all">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Link Next --}}
                    @if ($arsips->hasMorePages())
                        <a href="{{ $arsips->appends(request()->query())->nextPageUrl() }}" class="px-4 py-2 bg-emerald-900 hover:bg-emerald-950 text-white font-extrabold text-xs rounded-2xl shadow-md transition-all">Next</a>
                    @else
                        <span class="px-4 py-2 bg-emerald-100/50 dark:bg-emerald-950/40 text-emerald-300 dark:text-emerald-700 font-extrabold text-xs rounded-2xl cursor-not-allowed">Next</span>
                    @endif
                </div>
            </div>

        </div>
    </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const isDark = document.documentElement.classList.contains('dark');
    const bgPopup = isDark ? '#064e3b' : '#ffffff';
    const textColor = isDark ? '#f1f5f9' : '#064e3b';
    const cancelBtnColor = '#9ca3af'; 

    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonText: 'OK',
            confirmButtonColor: '#059669',
            background: bgPopup,
            color: textColor,
            customClass: {
                popup: 'rounded-[2.5rem] border border-emerald-50 dark:border-emerald-800 shadow-2xl',
                confirmButton: 'rounded-xl px-8 py-3 font-bold uppercase tracking-wider'
            }
        });
    @endif

    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Arsip Fisik?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#e5e7eb',
            confirmButtonText: 'Ya, Hapus Permanen!',
            cancelButtonText: 'Batalkan',
            background: bgPopup,
            color: textColor,
            customClass: {
                popup: 'rounded-[2.5rem] border border-red-50 dark:border-emerald-900 shadow-2xl',
                confirmButton: 'rounded-xl px-6 py-3 font-bold',
                cancelButton: '!text-gray-700 !font-bold rounded-xl px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('form-hapus-' + id).submit();
        });
    }
</script>

<style>
    /* Styling Scrollbar Khusus Pagination */
    .custom-pagination-scroll::-webkit-scrollbar {
        height: 10px;
    }
    .custom-pagination-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 20px;
    }
    .dark .custom-pagination-scroll::-webkit-scrollbar-track {
        background: #064e3b;
    }
    .custom-pagination-scroll::-webkit-scrollbar-thumb {
        background: #71717a;
        border-radius: 20px;
    }
    .custom-pagination-scroll::-webkit-scrollbar-thumb:hover {
        background: #52525b;
    }
</style>
@endsection