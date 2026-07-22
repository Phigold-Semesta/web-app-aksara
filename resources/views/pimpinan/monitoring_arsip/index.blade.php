@extends('layouts.app')

@section('title', 'Monitoring Arsip Surat')

@section('content')
<div class="p-8 min-h-screen transition-colors duration-300 dark:bg-emerald-950/20">
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6">
        <div>
            <p class="text-emerald-600 dark:text-emerald-400 font-black text-[10px] uppercase tracking-[0.3em]">Auditor Arsip</p>
            <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-white tracking-tight italic">Monitoring Arsip Surat</h1>
            <p class="text-emerald-600/70 dark:text-emerald-400/60 font-medium mt-1">Pemantauan dokumen yang telah diarsipkan secara digital</p>
        </div>
    </div>

    {{-- Control Bar Section (Search Box & Filter Jumlah Baris) --}}
    <div class="bg-white dark:bg-emerald-900/40 rounded-[2rem] p-6 mb-8 shadow-sm border border-emerald-50 dark:border-emerald-800/50 flex flex-wrap gap-4 items-center justify-between transition-all">
        
        <form action="{{ route('pimpinan.monitoring_arsip.index') }}" method="GET" id="filterForm" class="flex flex-wrap gap-4 w-full items-center justify-between">
            
            {{-- Filter Jumlah Baris --}}
            <div class="flex items-center gap-3">
                <span class="text-[11px] font-bold text-emerald-900 dark:text-emerald-200 uppercase">Tampilkan:</span>
                <div class="relative">
                    <select name="per_page" onchange="document.getElementById('filterForm').submit()" 
                        class="appearance-none bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-100/50 dark:border-emerald-800/50 rounded-xl px-5 py-2.5 pr-8 text-xs font-bold text-emerald-900 dark:text-emerald-100 focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer">
                        <option value="5" {{ request('per_page', 5) == 5 ? 'selected' : '' }}>5 Baris</option>
                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Baris</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 Baris</option>
                        <option value="-1" {{ request('per_page') == -1 ? 'selected' : '' }}>Semua Data</option>
                    </select>
                    <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-emerald-500 pointer-events-none"></i>
                </div>
            </div>

            {{-- Input Searching --}}
            <div class="flex items-center gap-3 w-full sm:w-80">
                <div class="relative w-full">
                    <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-emerald-400 text-xs"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari perihal atau nomor surat..." 
                        class="w-full bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-100/50 dark:border-emerald-800/50 rounded-xl pl-10 pr-4 py-2.5 text-xs font-medium text-emerald-900 dark:text-emerald-100 placeholder-emerald-300 outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
                
                @if(request('search') || request('per_page'))
                    <a href="{{ route('pimpinan.monitoring_arsip.index') }}" 
                       class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-red-100 transition-all flex items-center gap-2 border border-red-100 dark:border-red-900/30 shrink-0">
                        <i class="fas fa-times-circle"></i> Reset
                    </a>
                @endif
            </div>
            
        </form>
    </div>

    {{-- Table Section --}}
    <div class="overflow-x-auto">
        <table class="w-full border-separate border-spacing-y-4">
            <thead>
                <tr class="text-emerald-900/40 dark:text-emerald-100/30 uppercase text-[11px] font-black tracking-[0.2em]">
                    <th class="px-8 py-2 text-left">Informasi Surat</th>
                    <th class="px-8 py-2 text-left">Lokasi Fisik</th>
                    <th class="px-8 py-2 text-center">Tanggal Arsip</th>
                    <th class="px-8 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arsipSurat as $arsip)
                <tr class="bg-white dark:bg-emerald-900/20 hover:shadow-2xl hover:shadow-emerald-900/10 dark:hover:shadow-black/40 transition-all duration-300 group">
                    <td class="px-8 py-6 rounded-l-[2.5rem] border-y border-l border-emerald-50 dark:border-emerald-800/50">
                        <div class="flex flex-col">
                            <span class="text-emerald-950 dark:text-white font-bold text-lg mb-1">{{ $arsip->surat?->perihal ?? 'Surat Tidak Ditemukan' }}</span>
                            <span class="bg-emerald-50 dark:bg-emerald-800/40 px-2 py-0.5 rounded text-emerald-700 dark:text-emerald-300 font-bold border border-emerald-100 dark:border-emerald-700 w-fit text-xs">
                                {{ $arsip->surat?->nomor_surat ?? 'N/A' }}
                            </span>
                        </div>
                    </td>
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-emerald-800/50">
                        <span class="font-bold text-emerald-800 dark:text-emerald-200">{{ $arsip->lokasi_fisik }}</span>
                    </td>
                    <td class="px-8 py-6 border-y border-emerald-50 dark:border-emerald-800/50 text-center text-emerald-900 dark:text-emerald-100 font-bold">
                        {{ \Carbon\Carbon::parse($arsip->tanggal_arsip)->translatedFormat('d M Y') }}
                    </td>
                    <td class="px-8 py-6 rounded-r-[2.5rem] border-y border-r border-emerald-50 dark:border-emerald-800/50 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('pimpinan.monitoring_arsip.show', $arsip->id_arsip) }}" 
                                class="p-3 bg-emerald-50 dark:bg-emerald-900/40 text-emerald-600 dark:text-emerald-400 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Lihat Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('pimpinan.monitoring_arsip.download', $arsip->id_arsip) }}" 
                               class="p-3 bg-sky-50 dark:bg-sky-900/40 text-sky-600 dark:text-sky-400 rounded-xl hover:bg-sky-600 hover:text-white transition-all shadow-sm" 
                               title="Download">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-20 text-center text-emerald-400 font-bold italic">Belum ada data arsip.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- CUSTOM PAGINATION CONTAINER (SESUAI GAMBAR) --}}
    <div class="mt-8 bg-white dark:bg-emerald-900/40 rounded-[2.5rem] p-4 sm:p-5 shadow-sm border border-emerald-50 dark:border-emerald-800/50 flex flex-col sm:flex-row items-center justify-between gap-4 transition-all">
        
        {{-- Teks Informasi Data --}}
        <div class="text-[11px] font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-400 pl-4">
            MENAMPILKAN {{ $arsipSurat->firstItem() ?? 0 }} – {{ $arsipSurat->lastItem() ?? 0 }} DARI {{ $arsipSurat->total() }} DATA
        </div>

        {{-- Navigasi Tombol Pagination --}}
        <div class="flex items-center gap-2 pr-2">
            
            {{-- Tombol Prev --}}
            @if ($arsipSurat->onFirstPage())
                <span class="px-4 py-2 rounded-full text-xs font-bold bg-emerald-50/50 dark:bg-emerald-950/20 text-emerald-300 dark:text-emerald-700/50 cursor-not-allowed">
                    Prev
                </span>
            @else
                <a href="{{ $arsipSurat->previousPageUrl() }}" class="px-4 py-2 rounded-full text-xs font-bold bg-emerald-100/70 dark:bg-emerald-900/60 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-200 transition-all">
                    Prev
                </a>
            @endif

            {{-- Nomor Halaman --}}
            @foreach ($arsipSurat->getUrlRange(1, $arsipSurat->lastPage()) as $page => $url)
                @if ($page == $arsipSurat->currentPage())
                    <span class="w-9 h-9 flex items-center justify-center rounded-full text-xs font-black bg-[#006b43] text-white shadow-md shadow-emerald-900/20">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-full text-xs font-bold bg-emerald-100/70 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-200 hover:bg-emerald-200 transition-all">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($arsipSurat->hasMorePages())
                <a href="{{ $arsipSurat->nextPageUrl() }}" class="px-5 py-2 rounded-full text-xs font-black bg-[#006b43] text-white hover:bg-emerald-800 transition-all shadow-md shadow-emerald-900/20">
                    Next
                </a>
            @else
                <span class="px-5 py-2 rounded-full text-xs font-black bg-emerald-50/50 dark:bg-emerald-950/20 text-emerald-300 dark:text-emerald-700/50 cursor-not-allowed">
                    Next
                </span>
            @endif

        </div>

    </div>

</div>
@endsection