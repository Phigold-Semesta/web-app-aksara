@extends('layouts.app')

@section('title', 'Master Kategori Surat')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-emerald-900 p-6 rounded-3xl shadow-sm border border-emerald-50 dark:border-emerald-800 transition-colors">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Master Kategori Surat</h1>
            <p class="text-sm text-slate-500 dark:text-emerald-300/70 mt-1">Mengelola klasifikasi dan kategori dokumen surat pada sistem AKSARA LPSE Karawang.</p>
        </div>
        <a href="{{ route('admin.master.kategori.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#008f5d] hover:bg-emerald-700 text-white font-bold text-sm shadow-lg shadow-emerald-600/20 transition-all shrink-0">
            <i class="fas fa-folder-plus text-sm"></i>
            <span>Tambah Kategori Baru</span>
        </a>
    </div>

    <div class="bg-white dark:bg-emerald-900 rounded-3xl shadow-sm border border-emerald-50 dark:border-emerald-800 transition-colors overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-emerald-800 bg-slate-50/50 dark:bg-emerald-950/20 text-slate-400 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest">
                        <th class="py-4 px-6 text-center w-16">No</th>
                        <th class="py-4 px-6">Kode Kategori</th>
                        <th class="py-4 px-6">Nama Kategori Surat</th>
                        <th class="py-4 px-6">Keterangan / Deskripsi</th>
                        <th class="py-4 px-6 text-center w-36">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-emerald-800 text-sm text-slate-700 dark:text-emerald-100">
                    @forelse($kategori as $index => $kat)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-emerald-950/10 transition-colors">
                            <td class="py-4 px-6 text-center font-bold text-slate-400 dark:text-emerald-500">{{ $index + 1 }}</td>
                            
                            <td class="py-4 px-6 font-mono font-bold text-[#008f5d] dark:text-emerald-400">
                                {{ $kat->kode_kategori }}
                            </td>
                            
                            <td class="py-4 px-6 font-semibold text-slate-800 dark:text-white">{{ $kat->nama_kategori }}</td>
                            <td class="py-4 px-6 text-slate-500 dark:text-emerald-200/70 max-w-xs truncate">{{ $kat->keterangan ?? '-' }}</td>
                            
                            <td class="py-4 px-6 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.master.kategori.edit', $kat->id_kategori) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 hover:bg-amber-100 border border-amber-200/50 dark:border-amber-900 transition-colors" title="Ubah Kategori">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    {{-- FORM HAPUS: Diberikan ID unik agar selector JS tidak bingung --}}
                                    <form action="{{ route('admin.master.kategori.destroy', $kat->id_kategori) }}" method="POST" class="inline form-delete" data-nama="{{ $kat->nama_kategori }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-delete w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 hover:bg-rose-100 border border-rose-200/50 dark:border-rose-900 transition-colors" title="Hapus Kategori">
                                            <i class="fas fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 px-6 text-center text-slate-400 dark:text-emerald-600 font-medium">
                                <i class="fas fa-folder-open text-4xl mb-3 block"></i>
                                Belum ada data kategori surat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Notifikasi Sukses
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#008f5d',
                customClass: { popup: 'rounded-3xl' }
            });
        @endif

        // Konfirmasi Hapus dengan perbaikan selector untuk menjamin fungsi berjalan
        document.querySelectorAll('.form-delete').forEach(form => {
            const btn = form.querySelector('.btn-delete');
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const namaKategori = form.getAttribute('data-nama');
                
                Swal.fire({
                    title: 'Hapus Kategori?',
                    text: `Kategori "${namaKategori}" akan dihapus permanen!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    customClass: { popup: 'rounded-3xl' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection