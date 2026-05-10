@extends('layouts.app')

@section('content')
<div class="p-8 transition-colors duration-300">
    <div class="flex justify-between items-end mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight">Manajemen Surat</h1>
            <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Digitalisasi dan Pengarsipan Surat LPSE Karawang</p>
        </div>
        <a href="{{ route('petugas.manajemen_surat.create') }}" 
           class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-emerald-200 dark:shadow-emerald-900/20 transition-all flex items-center gap-3 transform hover:-translate-y-1">
            <i class="fas fa-plus-circle text-lg"></i>
            INPUT SURAT BARU
        </a>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="mb-8 flex flex-wrap gap-4 items-center justify-between">
        <form action="{{ route('petugas.manajemen_surat.index') }}" method="GET" class="flex flex-wrap gap-4 items-center w-full lg:w-auto">
            {{-- Filter Baris --}}
            <div class="relative">
                <select name="per_page" onchange="this.form.submit()" 
                        class="appearance-none bg-white dark:bg-slate-900 border border-emerald-100 dark:border-slate-800 text-emerald-900 dark:text-emerald-100 py-3 px-6 pr-10 rounded-2xl font-bold focus:ring-2 focus:ring-emerald-500 transition-all cursor-pointer shadow-sm">
                    <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5 Baris</option>
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Baris</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                    <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua Data</option>
                </select>
                <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-emerald-500">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </div>

            {{-- Search Input --}}
            <div class="relative flex-grow lg:w-80">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari perihal, instansi, atau nomor surat..."
                       class="w-full bg-white dark:bg-slate-900 border border-emerald-100 dark:border-slate-800 text-emerald-900 dark:text-emerald-100 py-3 px-12 rounded-2xl font-medium focus:ring-2 focus:ring-emerald-500 transition-all shadow-sm">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-emerald-400"></i>
                @if(request('search'))
                    <a href="{{ route('petugas.manajemen_surat.index') }}" class="absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-500">
                        <i class="fas fa-times-circle"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    {{-- Container Tabel --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 dark:shadow-black/20 overflow-hidden border border-emerald-50 dark:border-slate-800 transition-all">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-emerald-50/50 dark:bg-slate-800/50 border-b border-emerald-100 dark:border-slate-800">
                        <th class="px-8 py-6 text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest">No. Agenda</th>
                        <th class="px-8 py-6 text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest">Data Surat</th>
                        <th class="px-8 py-6 text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest text-center">Kategori</th>
                        <th class="px-8 py-6 text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest text-center">Status</th>
                        <th class="px-8 py-6 text-emerald-900 dark:text-emerald-100 font-black uppercase text-xs tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-50/80 dark:divide-slate-800">
                    @forelse($surats as $item)
                    <tr class="hover:bg-emerald-50/30 dark:hover:bg-slate-800/30 transition-colors group">
                        <td class="px-8 py-6 text-center">
                            <span class="text-emerald-950 dark:text-emerald-100 font-bold block">#{{ $item->id_surat }}</span>
                            <span class="text-emerald-400 dark:text-emerald-500 text-[10px] font-bold">{{ $item->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-emerald-950 dark:text-emerald-50 font-bold text-base group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">{{ $item->perihal }}</span>
                                <span class="text-emerald-500/80 dark:text-emerald-400/70 text-sm italic">{{ $item->asal_instansi }}</span>
                                <span class="text-emerald-400 dark:text-emerald-500/60 text-xs mt-1">No: {{ $item->nomor_surat }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-4 py-1.5 bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 rounded-lg text-[11px] font-black uppercase border border-emerald-200 dark:border-emerald-800">
                                {{ $item->kategori->nama_kategori ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($item->status == 'pending')
                                <span class="text-orange-500 dark:text-orange-400 font-bold text-xs uppercase flex items-center justify-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-orange-500 dark:bg-orange-400 rounded-full animate-pulse"></span> Diperiksa
                                </span>
                            @else
                                <span class="text-blue-500 dark:text-blue-400 font-bold text-xs uppercase flex items-center justify-center gap-1">
                                    <i class="fas fa-paper-plane text-[10px]"></i> {{ $item->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Aksi: Detail --}}
                                <a href="{{ route('petugas.manajemen_surat.show', $item->id_surat) }}" 
                                   class="group/btn p-2.5 bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 rounded-xl hover:bg-emerald-600 dark:hover:bg-emerald-600 transition-all shadow-sm" title="Detail">
                                    <i class="fas fa-eye text-sm group-hover/btn:text-white transition-colors"></i>
                                </a>

                                {{-- Aksi: Edit --}}
                                <a href="{{ route('petugas.manajemen_surat.edit', $item->id_surat) }}" 
                                   class="group/btn p-2.5 bg-amber-50 dark:bg-slate-800 text-amber-600 dark:text-amber-400 rounded-xl hover:bg-amber-500 dark:hover:bg-amber-500 transition-all shadow-sm" title="Edit">
                                    <i class="fas fa-edit text-sm group-hover/btn:text-white transition-colors"></i>
                                </a>
                                
                                {{-- Aksi: Teruskan --}}
                                <form action="{{ route('petugas.teruskan_pimpinan', $item->id_surat) }}" method="POST" id="form-teruskan-{{ $item->id_surat }}">
                                    @csrf @method('PATCH')
                                    <button type="button" onclick="konfirmasiTeruskan('{{ $item->id_surat }}')"
                                            class="group/btn p-2.5 bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 rounded-xl hover:bg-blue-600 dark:hover:bg-blue-600 transition-all shadow-sm" title="Teruskan">
                                        <i class="fas fa-share-square text-sm group-hover/btn:text-white transition-colors"></i>
                                    </button>
                                </form>

                                {{-- Aksi: Hapus --}}
                                <form action="{{ route('petugas.manajemen_surat.destroy', $item->id_surat) }}" method="POST" id="form-hapus-{{ $item->id_surat }}">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="konfirmasiHapus('{{ $item->id_surat }}')"
                                            class="group/btn p-2.5 bg-red-50 dark:bg-slate-800 text-red-600 dark:text-red-400 rounded-xl hover:bg-red-600 dark:hover:bg-red-600 transition-all shadow-sm" title="Hapus">
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
                                <i class="fas fa-search text-5xl text-emerald-200 mb-4"></i>
                                <p class="text-emerald-900 dark:text-emerald-100 font-bold">Data surat tidak ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer & Pagination --}}
        @if($surats instanceof \Illuminate\Pagination\LengthAwarePaginator && $surats->hasPages())
        <div class="px-8 py-6 bg-emerald-50/30 dark:bg-slate-800/30 border-t border-emerald-50 dark:border-slate-800">
            {{ $surats->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    const isDark = document.documentElement.classList.contains('dark');
    const bgPopup = isDark ? '#0f172a' : '#ffffff';
    const textColor = isDark ? '#f1f5f9' : '#064e3b';
    const cancelBtnColor = '#e5e7eb'; // Abu-abu muda

    // 1. Notifikasi Sukses
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            confirmButtonText: 'MANTAP, BOS!',
            confirmButtonColor: '#059669',
            background: bgPopup,
            color: textColor,
            customClass: {
                popup: 'rounded-[2rem] border border-emerald-50 dark:border-slate-800 shadow-2xl',
                confirmButton: 'rounded-xl px-8 py-3 font-bold uppercase tracking-wider'
            }
        });
    @endif

    // 2. Konfirmasi Teruskan ke Pimpinan
    function konfirmasiTeruskan(id) {
        Swal.fire({
            title: 'Teruskan ke Pimpinan?',
            text: "Surat akan dikirim ke dashboard Pimpinan untuk segera didisposisikan.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: cancelBtnColor,
            confirmButtonText: 'Ya, Teruskan!',
            cancelButtonText: '<span style="color: #374151">Batalkan</span>',
            background: bgPopup,
            color: textColor,
            customClass: {
                popup: 'rounded-[2.5rem] border border-emerald-50 dark:border-slate-800 shadow-2xl',
                confirmButton: 'rounded-xl px-6 py-3 font-bold',
                cancelButton: 'rounded-xl px-6 py-3 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('form-teruskan-' + id).submit();
        });
    }

    // 3. Konfirmasi Hapus Data
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Data Surat?',
            text: "Data yang dihapus tidak dapat dikembalikan! Pastikan arsip fisik sudah aman.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: cancelBtnColor,
            confirmButtonText: 'Ya, Hapus Permanen!',
            cancelButtonText: '<span style="color: #374151">Batalkan</span>',
            background: bgPopup,
            color: textColor,
            customClass: {
                popup: 'rounded-[2.5rem] border border-red-50 dark:border-slate-800 shadow-2xl',
                confirmButton: 'rounded-xl px-6 py-3 font-bold',
                cancelButton: 'rounded-xl px-6 py-3 font-bold'
            }
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('form-hapus-' + id).submit();
        });
    }
</script>

<style>
    .pagination { @apply flex gap-2; }
    .page-item.active .page-link { @apply bg-emerald-600 border-emerald-600 text-white rounded-xl shadow-lg shadow-emerald-200; }
    .page-link { @apply border-none bg-emerald-50 text-emerald-700 font-bold px-4 py-2 rounded-xl hover:bg-emerald-100 transition-all shadow-sm; }
    .dark .page-link { @apply bg-slate-800 text-emerald-400 hover:bg-slate-700; }
</style>
@endsection