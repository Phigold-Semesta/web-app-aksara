@extends('layouts.app')

@section('content')
<div class="p-8 min-h-screen transition-colors duration-300 dark:bg-emerald-950/20">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6">
        <div>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-white tracking-tight">Manajemen Surat (Admin)</h1>
            <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Digitalisasi dan Pengarsipan Surat LPSE Karawang</p>
        </div>
        <a href="{{ route('admin.manajemen_surat.create') }}" 
           class="bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-500 dark:hover:bg-emerald-400 text-white px-8 py-3.5 rounded-2xl font-bold transition-all shadow-lg shadow-emerald-200 dark:shadow-emerald-900/20 flex items-center gap-3 transform hover:-translate-y-1 active:scale-95 uppercase tracking-wider text-sm">
            <i class="fas fa-plus-circle text-lg"></i>
            INPUT SURAT BARU
        </a>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="bg-white dark:bg-emerald-900/40 rounded-[2rem] p-6 mb-8 shadow-sm border border-emerald-50 dark:border-emerald-800/50 flex flex-wrap gap-4 items-center justify-between transition-all">
        <form action="{{ route('admin.manajemen_surat.index') }}" method="GET" class="flex flex-wrap gap-3 w-full lg:w-auto">
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
                <a href="{{ route('admin.manajemen_surat.index') }}" class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-5 py-3 rounded-2xl font-bold hover:bg-red-100 transition-all flex items-center gap-2 border border-red-100 dark:border-red-900/30">
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
                            <a href="{{ route('admin.manajemen_surat.show', $item->id_surat) }}" class="group/btn p-2.5 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-xl hover:bg-emerald-600 dark:hover:bg-emerald-500 transition-all shadow-sm" title="Lihat">
                                <i class="fas fa-eye text-sm group-hover/btn:text-white"></i>
                            </a>
                            <a href="{{ route('admin.manajemen_surat.edit', $item->id_surat) }}" class="group/btn p-2.5 bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 rounded-xl hover:bg-amber-600 dark:hover:bg-amber-500 transition-all shadow-sm" title="Edit">
                                <i class="fas fa-edit text-sm group-hover/btn:text-white"></i>
                            </a>
                            {{-- Form Teruskan ke Pimpinan --}}
                            <form action="{{ route('admin.manajemen_surat.teruskan', $item->id_surat) }}" method="POST" id="form-teruskan-{{ $item->id_surat }}" class="inline">
                                @csrf @method('PATCH')
                                <button type="button" onclick="konfirmasiTeruskan('{{ $item->id_surat }}')" class="group/btn p-2.5 bg-blue-50 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400 rounded-xl hover:bg-blue-600 dark:hover:bg-blue-500 transition-all shadow-sm" title="Teruskan ke Pimpinan">
                                    <i class="fas fa-paper-plane text-sm group-hover/btn:text-white"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.manajemen_surat.destroy', $item->id_surat) }}" method="POST" id="form-hapus-{{ $item->id_surat }}" class="inline">
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

    {{-- Pagination --}}
    @if($surats instanceof \Illuminate\Pagination\LengthAwarePaginator && $surats->hasPages())
    <div class="mt-10 px-4">
        <div class="bg-white dark:bg-emerald-900/20 p-4 rounded-[2rem] border border-emerald-50 dark:border-emerald-800/50 shadow-sm flex justify-center">
            {{ $surats->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

{{-- Script SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const isDark = document.documentElement.classList.contains('dark');
    const bgPopup = isDark ? '#064e3b' : '#ffffff';
    const textColor = isDark ? '#f1f5f9' : '#064e3b';

    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", confirmButtonColor: '#059669', background: bgPopup, color: textColor, customClass: { popup: 'rounded-[2.5rem]' } });
    @endif

    function konfirmasiTeruskan(id) {
        Swal.fire({ 
            title: 'Teruskan Surat?', 
            text: "Surat akan diteruskan ke pimpinan untuk proses disposisi.", 
            icon: 'question', 
            showCancelButton: true, 
            confirmButtonColor: '#2563eb', 
            cancelButtonColor: '#9ca3af', 
            confirmButtonText: 'Ya, Teruskan!', 
            background: bgPopup, 
            color: textColor, 
            customClass: { popup: 'rounded-[2.5rem]' } 
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('form-teruskan-' + id).submit();
        });
    }

    function konfirmasiHapus(id) {
        Swal.fire({ 
            title: 'Hapus Data?', 
            text: "Data tidak dapat dikembalikan!", 
            icon: 'warning', 
            showCancelButton: true, 
            confirmButtonColor: '#dc2626', 
            cancelButtonColor: '#9ca3af', 
            confirmButtonText: 'Ya, Hapus!', 
            background: bgPopup, 
            color: textColor, 
            customClass: { popup: 'rounded-[2.5rem]' } 
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('form-hapus-' + id).submit();
        });
    }
</script>

<style>
    .pagination { @apply flex gap-2; }
    .pagination li { @apply list-none; }
    .page-item.active .page-link { @apply bg-emerald-600 border-emerald-600 text-white rounded-xl shadow-lg; }
    .page-link { @apply border-none bg-emerald-50 text-emerald-700 font-bold px-4 py-2 rounded-xl hover:bg-emerald-100 transition-all; }
    .dark .page-link { @apply bg-emerald-900/40 text-emerald-400 hover:bg-emerald-800; }
</style>
@endsection