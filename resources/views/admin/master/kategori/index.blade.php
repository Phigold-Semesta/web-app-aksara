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
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl font-black text-emerald-950 dark:text-white uppercase italic">Daftar Kategori</h2>
            <a href="{{ route('admin.master.kategori.create') }}" class="bg-emerald-900 dark:bg-emerald-600 text-emerald-50 px-6 py-3 rounded-xl font-black uppercase text-xs hover:bg-emerald-800 dark:hover:bg-emerald-500 transition-all flex items-center gap-2 shadow-lg">
                <i class="fas fa-plus"></i> Tambah Kategori
            </a>
        </div>

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
                    <tr class="bg-emerald-50/50 dark:bg-emerald-950/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-all duration-300 rounded-2xl group shadow-sm">
                        <td class="px-6 py-4 font-black text-emerald-700 dark:text-emerald-300 rounded-l-2xl">{{ $kat->kode_kategori }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-900 dark:text-emerald-100">{{ $kat->nama_kategori }}</td>
                        <td class="px-6 py-4 text-emerald-600 dark:text-emerald-400 text-sm">{{ $kat->keterangan ?? '-' }}</td>
                        
                        <td class="px-6 py-4 text-center rounded-r-2xl">
                            <div class="flex justify-center items-center gap-3">
                                {{-- Edit Button --}}
                                <a href="{{ route('admin.master.kategori.edit', $kat->id_kategori) }}" 
                                   class="bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 hover:bg-amber-500 hover:text-white transition-all duration-300 p-2.5 rounded-lg flex items-center justify-center shadow-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                {{-- Delete Form --}}
                                <form id="delete-form-{{ $kat->id_kategori }}" action="{{ route('admin.master.kategori.destroy', $kat->id_kategori) }}" method="POST">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="button" onclick="confirmDelete('{{ $kat->id_kategori }}')"
                                            class="bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all duration-300 p-2.5 rounded-lg flex items-center justify-center shadow-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center py-10 text-emerald-400 font-bold italic">Belum ada data kategori yang tersedia.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Konfirmasi Hapus
    function confirmDelete(id) {
        Swal.fire({
            icon: 'warning',
            title: 'Hapus Kategori?',
            text: "Data kategori ini akan dihapus permanen.",
            showCancelButton: true,
            confirmButtonText: 'YA, HAPUS',
            cancelButtonText: 'BATAL',
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-[2rem] p-6',
                confirmButton: 'bg-red-500 text-white px-8 py-3 rounded-xl font-black uppercase text-sm hover:bg-red-600 transition-all mx-2 shadow-lg',
                cancelButton: 'bg-slate-600 text-white px-8 py-3 rounded-xl font-black uppercase text-sm hover:bg-slate-700 transition-all mx-2 shadow-lg',
                title: 'text-2xl font-black text-slate-800 uppercase italic',
                htmlContainer: 'text-slate-600 font-medium'
            },
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    // Notifikasi Sukses
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'BERHASIL!',
            text: "{{ session('success') }}",
            confirmButtonText: 'OK',
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-[2rem] p-6',
                confirmButton: 'bg-emerald-600 text-white px-10 py-3 rounded-xl font-black uppercase text-sm hover:bg-emerald-700 transition-all shadow-lg',
                title: 'text-2xl font-black text-emerald-900 uppercase italic'
            }
        });
    @endif
</script>
@endsection