@extends('layouts.app')

@section('content')
<div class="p-8 transition-colors duration-300">
    <div class="flex justify-between items-end mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight">Manajemen Surat</h1>
            <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Digitalisasi dan Pengarsipan Surat LPSE Karawang</p>
        </div>
        <a href="{{ route('admin.manajemen_surat.create') }}" 
           class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-emerald-200 dark:shadow-emerald-900/20 transition-all flex items-center gap-3 transform hover:-translate-y-1">
            <i class="fas fa-plus-circle text-lg"></i>
            INPUT SURAT BARU
        </a>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="mb-8 flex flex-wrap gap-4 items-center justify-between">
        <form action="{{ route('admin.manajemen_surat.index') }}" method="GET" class="flex flex-wrap gap-4 items-center w-full lg:w-auto">
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

            <div class="relative flex-grow lg:w-80">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari perihal, instansi, atau nomor surat..."
                       class="w-full bg-white dark:bg-slate-900 border border-emerald-100 dark:border-slate-800 text-emerald-900 dark:text-emerald-100 py-3 px-12 rounded-2xl font-medium focus:ring-2 focus:ring-emerald-500 transition-all shadow-sm">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-emerald-400"></i>
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
                                <span class="text-emerald-950 dark:text-emerald-50 font-bold text-base group-hover:text-emerald-600 transition-colors">{{ $item->perihal }}</span>
                                <span class="text-emerald-500/80 dark:text-emerald-400/70 text-sm italic">{{ $item->asal_instansi }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="text-blue-500 dark:text-blue-400 font-bold text-xs uppercase">{{ $item->status }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Tombol Show --}}
                                <a href="{{ route('admin.manajemen_surat.show', $item->id_surat) }}" class="p-2.5 bg-emerald-50 dark:bg-slate-800 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all" title="Lihat">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.manajemen_surat.edit', $item->id_surat) }}" class="p-2.5 bg-amber-50 dark:bg-slate-800 text-amber-600 rounded-xl hover:bg-amber-600 hover:text-white transition-all" title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                {{-- Tombol Hapus --}}
                                <form action="{{ route('admin.manajemen_surat.destroy', $item->id_surat) }}" method="POST" id="form-hapus-{{ $item->id_surat }}">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="konfirmasiHapus('{{ $item->id_surat }}')" class="p-2.5 bg-red-50 dark:bg-slate-800 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all" title="Hapus">
                                        <i class="fas fa-trash-alt text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="px-8 py-20 text-center font-bold text-emerald-600">Data tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        @if($surats instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="px-8 py-6 bg-emerald-50/30 border-t border-emerald-50">
            {{ $surats->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Data Surat?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus Permanen!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-hapus-' + id).submit();
            }
        });
    }
</script>
@endsection