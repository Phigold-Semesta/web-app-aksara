@extends('layouts.app')

@section('title', 'Manajemen Surat')

@section('content')
<div class="p-8 min-h-screen transition-colors duration-300 dark:bg-emerald-950/20">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-white tracking-tight">Manajemen Surat</h1>
            <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Digitalisasi dan Pengarsipan Surat LPSE Karawang</p>
        </div>
        <a href="{{ route('petugas.manajemen_surat.create') }}" 
           class="bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-400 text-white px-8 py-3.5 rounded-2xl font-bold transition-all shadow-lg shadow-emerald-200 dark:shadow-emerald-900/20 flex items-center gap-3 transform hover:-translate-y-1 active:scale-95 uppercase tracking-wider text-sm">
            <i class="fas fa-plus-circle text-lg"></i>
            INPUT SURAT BARU
        </a>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="bg-white dark:bg-emerald-900/40 rounded-[2rem] p-6 mb-8 shadow-sm border border-emerald-50 dark:border-emerald-800/50 flex flex-wrap gap-4 items-center justify-between transition-all">
        <form action="{{ route('petugas.manajemen_surat.index') }}" method="GET" class="flex flex-wrap gap-3 w-full lg:w-auto">
            <div class="relative">
                <select name="per_page" onchange="this.form.submit()" 
                        class="appearance-none bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-100/50 dark:border-emerald-800/50 rounded-2xl px-6 py-3 pr-10 focus:ring-2 focus:ring-emerald-500 text-emerald-900 dark:text-emerald-100 font-bold transition-all cursor-pointer shadow-sm">
                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5 Baris</option>
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Baris</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua Data</option>
                </select>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-emerald-500">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </div>

            <div class="relative flex-grow lg:w-80">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-emerald-400"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari perihal, instansi, atau nomor surat..."
                       class="w-full bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-100/50 dark:border-emerald-800/50 rounded-2xl pl-12 pr-5 py-3 focus:ring-2 focus:ring-emerald-500 text-emerald-900 dark:text-emerald-100 placeholder-emerald-300 transition-all font-medium shadow-sm">
            </div>

            @if(request('search') || request('per_page'))
                <a href="{{ route('petugas.manajemen_surat.index') }}" class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-5 py-3 rounded-2xl font-bold hover:bg-red-100 transition-all flex items-center gap-2 border border-red-100 dark:border-red-900/30">
                    <i class="fas fa-times-circle"></i> Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Container Tabel --}}
    <div class="overflow-x-auto">
        <table class="w-full border-separate border-spacing-y-4">
            <thead>
                <tr class="text-emerald-900/40 dark:text-emerald-100/30 uppercase text-[11px] font-black tracking-[0.2em]">
                    <th class="px-8 py-2 text-center">No. Agenda</th>
                    <th class="px-8 py-2 text-left">Data Surat</th>
                    <th class="px-8 py-2 text-center">Status</th>
                    <th class="px-8 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($surats as $item)
                <tr class="bg-white dark:bg-emerald-900/20 hover:shadow-2xl hover:shadow-emerald-900/10 dark:hover:shadow-black/40 transition-all duration-300 group">
                    <td class="px-8 py-6 rounded-l-[2.5rem] border-y border-l border-emerald-50 dark:border-emerald-800/50 text-center">
                        <span class="text-emerald-950 dark:text-white font-bold text-lg block group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">#{{ $item->id_surat }}</span>
                        <span class="text-emerald-500/80 dark:text-emerald-400/60 text-xs font-bold mt-1 block">{{ $item->created_at->format('d M Y') }}</span>
                    </td>
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-emerald-800/50">
                        <div class="flex flex-col">
                            <span class="text-emerald-950 dark:text-white font-bold text-lg mb-1 line-clamp-1 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">{{ $item->perihal }}</span>
                            <div class="flex items-center gap-2 text-xs font-medium text-emerald-500/80 dark:text-emerald-400/60">
                                <i class="fas fa-building text-[10px]"></i>
                                <span class="italic">{{ $item->asal_instansi }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-emerald-800/50 text-center">
                        @php $status = strtolower($item->status); @endphp
                        @if($status == 'belum dikirim')
                            <span class="bg-amber-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full shadow-lg uppercase tracking-widest">Belum Dikirim</span>
                        @elseif($status == 'pending')
                            <span class="bg-blue-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full shadow-lg uppercase tracking-widest">Pending</span>
                        @elseif($status == 'diarsipkan')
                            <span class="bg-emerald-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full shadow-lg uppercase tracking-widest">Diarsipkan</span>
                        @else
                            <span class="bg-indigo-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full shadow-lg uppercase tracking-widest">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 rounded-r-[2.5rem] border-y border-r border-emerald-50 dark:border-emerald-800/50 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('petugas.manajemen_surat.show', $item->id_surat) }}" class="group/btn p-2.5 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-xl hover:bg-emerald-600 dark:hover:bg-emerald-500 transition-all shadow-sm" title="Lihat">
                                <i class="fas fa-eye text-sm group-hover/btn:text-white"></i>
                            </a>
                            <a href="{{ route('petugas.manajemen_surat.edit', $item->id_surat) }}" class="group/btn p-2.5 bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 rounded-xl hover:bg-amber-500 dark:hover:bg-amber-500 transition-all shadow-sm" title="Edit">
                                <i class="fas fa-edit text-sm group-hover/btn:text-white"></i>
                            </a>
                            <form action="{{ route('petugas.teruskan_pimpinan', $item->id_surat) }}" method="POST" id="form-teruskan-{{ $item->id_surat }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="button" onclick="konfirmasiTeruskan('{{ $item->id_surat }}')" class="group/btn p-2.5 bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-xl hover:bg-blue-600 dark:hover:bg-blue-500 transition-all shadow-sm" title="Teruskan">
                                    <i class="fas fa-paper-plane text-sm group-hover/btn:text-white"></i>
                                </button>
                            </form>
                            <form action="{{ route('petugas.manajemen_surat.destroy', $item->id_surat) }}" method="POST" id="form-hapus-{{ $item->id_surat }}" class="inline">
                                @csrf @method('DELETE')
                                <button type="button" onclick="konfirmasiHapus('{{ $item->id_surat }}')" class="group/btn p-2.5 bg-red-50 dark:bg-red-900/40 text-red-600 dark:text-red-400 rounded-xl hover:bg-red-600 dark:hover:bg-red-500 transition-all shadow-sm" title="Hapus">
                                    <i class="fas fa-trash-alt text-sm group-hover/btn:text-white"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-8 py-20 text-center text-emerald-600 font-bold">Data tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PERBAIKAN: Pagination Sesuai Desain Gambar Referensi --}}
    @if($surats instanceof \Illuminate\Pagination\LengthAwarePaginator && $surats->hasPages())
    <div class="mt-8">
        <div class="bg-white dark:bg-emerald-900/30 p-6 rounded-[2.5rem] border border-emerald-50 dark:border-emerald-800/50 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
            
            {{-- Keterangan Menampilkan Data (Sesuai Teks di Gambar) --}}
            <div class="text-[11px] font-black uppercase tracking-wider text-emerald-600/70 dark:text-emerald-400/70">
                MENAMPILKAN {{ $surats->firstItem() }} – {{ $surats->lastItem() }} DARI {{ $surats->total() }} DATA
            </div>

            {{-- Container Tombol Pagination yang Bisa Di-scroll Horisontal --}}
            <div class="overflow-x-auto max-w-full pb-2 pt-1 px-2 custom-pagination-scroll">
                <div class="flex items-center gap-2 min-w-max">
                    {{-- Link Previous --}}
                    @if ($surats->onFirstPage())
                        <span class="px-4 py-2 bg-emerald-100/50 dark:bg-emerald-950/40 text-emerald-300 dark:text-emerald-700 font-extrabold text-xs rounded-2xl cursor-not-allowed">Prev</span>
                    @else
                        <a href="{{ $surats->previousPageUrl() }}" class="px-4 py-2 bg-emerald-200/60 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-200 hover:bg-emerald-300 font-extrabold text-xs rounded-2xl transition-all">Prev</a>
                    @endif

                    {{-- Elemen Angka Halaman --}}
                    @foreach ($surats->getUrlRange(1, $surats->lastPage()) as $page => $url)
                        @if ($page == $surats->currentPage())
                            <span class="w-9 h-9 flex items-center justify-center bg-emerald-900 text-white font-black text-xs rounded-2xl shadow-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center bg-emerald-200/60 dark:bg-emerald-900/40 text-emerald-800 dark:text-emerald-200 hover:bg-emerald-300 dark:hover:bg-emerald-800 font-bold text-xs rounded-2xl transition-all">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Link Next --}}
                    @if ($surats->hasMorePages())
                        <a href="{{ $surats->nextPageUrl() }}" class="px-4 py-2 bg-emerald-900 hover:bg-emerald-950 text-white font-extrabold text-xs rounded-2xl shadow-md transition-all">Next</a>
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
            confirmButtonColor: '#059669', 
            background: bgPopup, 
            color: textColor, 
            customClass: { popup: 'rounded-[2.5rem]' } 
        });
    @endif

    @if(session('error'))
        Swal.fire({ 
            icon: 'error', 
            title: 'Oops...', 
            text: "{{ session('error') }}", 
            confirmButtonColor: '#dc2626', 
            background: bgPopup, 
            color: textColor, 
            customClass: { popup: 'rounded-[2.5rem]' } 
        });
    @endif

    function konfirmasiHapus(id) {
        Swal.fire({ 
            title: 'Hapus Data?', 
            text: "Data tidak dapat dikembalikan!", 
            icon: 'warning', 
            showCancelButton: true, 
            confirmButtonColor: '#dc2626', 
            cancelButtonColor: '#e5e7eb', // PERBAIKAN: Warna background abu-abu muda sesuai gambar
            confirmButtonText: 'Ya, Hapus!', 
            cancelButtonText: 'Batalkan', 
            background: bgPopup, 
            color: textColor, 
            customClass: { 
                popup: 'rounded-[2.5rem]',
                cancelButton: '!text-gray-700 !font-bold' // PERBAIKAN: Warna teks abu-abu tua sesuai gambar
            } 
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('form-hapus-' + id).submit();
        });
    }

    function konfirmasiTeruskan(id) {
        Swal.fire({ 
            title: 'Teruskan Surat?', 
            text: "Surat akan dikirim ke Pimpinan untuk ditinjau.", 
            icon: 'question', 
            showCancelButton: true, 
            confirmButtonColor: '#2563eb', 
            cancelButtonColor: '#e5e7eb', // PERBAIKAN: Warna background abu-abu muda sesuai gambar
            confirmButtonText: 'Ya, Teruskan!', 
            cancelButtonText: 'Batalkan', 
            background: bgPopup, 
            color: textColor, 
            customClass: { 
                popup: 'rounded-[2.5rem]',
                cancelButton: '!text-gray-700 !font-bold' // PERBAIKAN: Warna teks abu-abu tua sesuai gambar
            } 
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('form-teruskan-' + id).submit();
        });
    }
</script>

<style>
    /* Styling Scrollbar Khusus Pagination seperti di gambar referensi */
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