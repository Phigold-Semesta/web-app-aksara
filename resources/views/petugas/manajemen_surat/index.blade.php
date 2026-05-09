@extends('layouts.app')

@section('content')
<div class="p-8 transition-colors duration-300">
    <div class="flex justify-between items-end mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight">Manajemen Surat</h1>
            <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Digitalisasi dan Pengarsipan Surat LPSE Karawang</p>
        </div>
        <a href="{{ route('petugas.manajemen_surat.create') }}" 
           class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-emerald-200 dark:shadow-emerald-900/20 transition-all flex items-center gap-3 transform hover:-translate-y-1"
           title="Klik untuk input data surat baru">
            <i class="fas fa-plus-circle text-lg"></i>
            INPUT SURAT BARU
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-100 dark:bg-emerald-900/30 border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-300 rounded-xl shadow-sm">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    </div>
    @endif

    {{-- Container Tabel dengan dukungan Dark Mode --}}
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
                        <td class="px-8 py-6">
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
                        <td class="px-8 py-6 text-center">
                            <div class="flex items-center justify-center gap-3">
                                {{-- Aksi: Detail --}}
                                <a href="{{ route('petugas.manajemen_surat.show', $item->id_surat) }}" 
                                   class="p-2.5 bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 rounded-xl hover:bg-emerald-600 dark:hover:bg-emerald-500 hover:text-white dark:hover:text-white transition-all shadow-sm" 
                                   title="Lihat Detail Surat">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>

                                {{-- Aksi: Edit --}}
                                <a href="{{ route('petugas.manajemen_surat.edit', $item->id_surat) }}" 
                                   class="p-2.5 bg-amber-50 dark:bg-slate-800 text-amber-600 dark:text-amber-400 rounded-xl hover:bg-amber-500 dark:hover:bg-amber-500 hover:text-white dark:hover:text-white transition-all shadow-sm" 
                                   title="Edit Data Surat">
                                    <i class="fas fa-edit text-sm"></i>
                                </a>
                                
                                {{-- Aksi: Teruskan ke Pimpinan --}}
                                <form action="{{ route('petugas.teruskan_pimpinan', $item->id_surat) }}" method="POST">
                                    @csrf 
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="p-2.5 bg-blue-50 dark:bg-slate-800 text-blue-600 dark:text-blue-400 rounded-xl hover:bg-blue-600 dark:hover:bg-blue-500 hover:text-white dark:hover:text-white transition-all shadow-sm" 
                                            title="Teruskan ke Pimpinan"
                                            onclick="return confirm('Apakah Anda yakin ingin meneruskan surat ini ke Pimpinan?')">
                                        <i class="fas fa-share-square text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-folder-open text-5xl text-emerald-100 dark:text-slate-800 mb-4"></i>
                                <p class="text-emerald-400 dark:text-emerald-600 italic font-medium">Belum ada data surat yang diarsipkan hari ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer Tabel & Pagination dengan Dark Mode --}}
        @if($surats->hasPages())
        <div class="px-8 py-6 bg-emerald-50/30 dark:bg-slate-800/30 border-t border-emerald-50 dark:border-slate-800">
            <div class="dark:invert dark:brightness-0 dark:contrast-200">
                {{ $surats->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection