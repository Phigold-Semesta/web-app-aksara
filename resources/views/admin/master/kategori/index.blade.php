@extends('layouts.app')

@section('title', 'Master Kategori Surat - AKSARA')

@section('content')
<div class="p-4 md:p-6 space-y-6 animate__animated animate__fadeIn">
    
    {{-- Header Section --}}
    <div class="bg-gradient-to-r from-emerald-900 to-emerald-700 dark:from-emerald-950 dark:to-emerald-900 rounded-[2rem] p-8 text-white shadow-2xl relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-4xl font-black uppercase tracking-tight italic text-white">MASTER KATEGORI SURAT</h1>
            <p class="text-emerald-200 font-bold tracking-widest mt-2 uppercase text-sm">AKSARA - Sistem Informasi Digital LPSE Karawang</p>
        </div>
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
    </div>

    {{-- Content Section --}}
    <div class="bg-white dark:bg-emerald-900/40 p-6 md:p-8 rounded-[2rem] shadow-xl border border-emerald-50 dark:border-emerald-800/50">
        
        {{-- Toolbar --}}
        <div class="flex flex-col lg:flex-row justify-between items-center mb-8 gap-4">
            <h2 class="text-xl font-black text-emerald-950 dark:text-white uppercase italic">Daftar Kategori</h2>
            
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto justify-end">
                <form action="{{ route('admin.master.kategori.index') }}" method="GET" class="flex flex-wrap gap-2 items-center">
                    <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" 
                        class="bg-emerald-50 dark:bg-emerald-950 border-emerald-200 dark:border-emerald-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none w-full sm:w-40">
                    
                    <select name="per_page" onchange="this.form.submit()" class="bg-emerald-50 dark:bg-emerald-950 border-emerald-200 dark:border-emerald-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer">
                        <option value="5" {{ request('per_page') == 5 ? 'selected' : '' }}>5 Baris</option>
                        <option value="10" {{ request('per_page') == 10 || !request('per_page') ? 'selected' : '' }}>10 Baris</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>
                    
                    <button type="submit" class="bg-emerald-700 text-white px-5 py-3 rounded-xl font-black text-xs hover:bg-emerald-800 transition-all shadow-lg">
                        FILTER
                    </button>
                </form>

                <a href="{{ route('admin.master.kategori.create') }}" class="bg-emerald-700 text-white px-6 py-3 rounded-xl font-black uppercase text-xs hover:bg-emerald-800 transition-all flex items-center gap-2 shadow-lg shrink-0">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </a>
            </div>
        </div>

        {{-- Table Section --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-y-4">
                <thead>
                    <tr class="text-emerald-500 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest">
                        <th class="px-6 py-3">Kode</th>
                        <th class="px-6 py-3">Nama Kategori</th>
                        <th class="px-6 py-3">Keterangan</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategori as $kat)
                    <tr class="bg-emerald-50/50 dark:bg-emerald-950/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-all duration-300 rounded-2xl shadow-sm">
                        <td class="px-6 py-4 font-black text-emerald-700 dark:text-emerald-300 rounded-l-2xl">{{ $kat->kode_kategori }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-900 dark:text-emerald-100">{{ $kat->nama_kategori }}</td>
                        <td class="px-6 py-4 text-emerald-600 dark:text-emerald-400 text-sm">{{ $kat->keterangan ?? '-' }}</td>
                        
                        <td class="px-6 py-4 text-center rounded-r-2xl">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('admin.master.kategori.edit', $kat->id_kategori) }}" class="bg-amber-100 text-amber-700 p-2.5 rounded-lg hover:bg-amber-500 hover:text-white transition-all"><i class="fas fa-pen-to-square"></i></a>
                                <form id="delete-form-{{ $kat->id_kategori }}" action="{{ route('admin.master.kategori.destroy', $kat->id_kategori) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $kat->id_kategori }}', '{{ $kat->nama_kategori }}')" class="bg-red-100 text-red-600 p-2.5 rounded-lg hover:bg-red-600 hover:text-white transition-all"><i class="fas fa-trash-can"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-10 text-emerald-400 font-bold italic">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Luxurious Pagination (Posisi Rata Kanan) --}}
        @if($kategori instanceof \Illuminate\Pagination\LengthAwarePaginator && $kategori->hasPages())
            <div class="mt-6 flex justify-end">
                <nav class="flex items-center gap-2">
                    @if($kategori->onFirstPage())
                        <span class="px-4 py-2 bg-emerald-100 text-emerald-400 rounded-xl cursor-not-allowed font-bold text-sm">Prev</span>
                    @else
                        <a href="{{ $kategori->previousPageUrl() }}" class="px-4 py-2 bg-emerald-700 text-white rounded-xl hover:bg-emerald-800 transition-all font-bold text-sm shadow-md">Prev</a>
                    @endif

                    @foreach ($kategori->links()->elements[0] as $page => $url)
                        @if ($page == $kategori->currentPage())
                            <span class="px-4 py-2 bg-emerald-900 text-white rounded-xl font-black text-sm shadow-lg">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="px-4 py-2 bg-emerald-50 text-emerald-800 rounded-xl hover:bg-emerald-200 transition-all font-bold text-sm">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($kategori->hasMorePages())
                        <a href="{{ $kategori->nextPageUrl() }}" class="px-4 py-2 bg-emerald-700 text-white rounded-xl hover:bg-emerald-800 transition-all font-bold text-sm shadow-md">Next</a>
                    @else
                        <span class="px-4 py-2 bg-emerald-100 text-emerald-400 rounded-xl cursor-not-allowed font-bold text-sm">Next</span>
                    @endif
                </nav>
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(id, name) {
        Swal.fire({
            icon: 'warning',
            title: 'HAPUS KATEGORI?',
            text: "Kategori '" + name + "' akan dihapus permanen.",
            showCancelButton: true,
            confirmButtonText: 'YA, HAPUS',
            cancelButtonText: 'BATAL',
            reverseButtons: true,
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-[2rem] p-6',
                confirmButton: 'bg-red-500 text-white px-8 py-3 rounded-xl font-black uppercase text-sm hover:bg-red-600 transition-all mx-2 shadow-lg',
                cancelButton: 'bg-slate-600 text-white px-8 py-3 rounded-xl font-black uppercase text-sm hover:bg-slate-700 transition-all mx-2 shadow-lg',
                title: 'text-2xl font-black text-slate-800 uppercase italic'
            }
        }).then((result) => { if (result.isConfirmed) document.getElementById('delete-form-' + id).submit(); });
    }
    @if(session('success'))
        Swal.fire({ icon: 'success', title: 'BERHASIL!', text: "{{ session('success') }}", confirmButtonColor: '#065f46', confirmButtonText: 'OK', customClass: { popup: 'rounded-[2rem]' } });
    @endif
</script>
@endsection