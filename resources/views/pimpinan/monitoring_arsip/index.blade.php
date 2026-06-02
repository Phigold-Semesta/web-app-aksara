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

    {{-- Filter Section --}}
    <div class="bg-white dark:bg-emerald-900/40 rounded-[2rem] p-6 mb-8 shadow-sm border border-emerald-50 dark:border-emerald-800/50 flex flex-wrap gap-4 items-center justify-between transition-all">
        <form action="{{ route('pimpinan.monitoring_arsip.index') }}" method="GET" class="flex flex-wrap gap-3 w-full lg:w-auto">
            <div class="relative flex-grow lg:w-80">
                <i class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-emerald-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari perihal atau nomor surat..." 
                    class="w-full bg-emerald-50/50 dark:bg-emerald-950/30 border border-emerald-100/50 dark:border-emerald-800/50 rounded-2xl pl-12 pr-5 py-3 focus:ring-2 focus:ring-emerald-500 text-emerald-900 dark:text-emerald-100 placeholder-emerald-300 transition-all font-medium">
            </div>
            @if(request('search'))
                <a href="{{ route('pimpinan.monitoring_arsip.index') }}" class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-5 py-3 rounded-2xl font-bold hover:bg-red-100 transition-all flex items-center gap-2 border border-red-100 dark:border-red-900/30">
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
                    <th class="px-8 py-2 text-center">Tanggal Arsip</th>
                    <th class="px-8 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arsipSurat as $arsip)
                <tr class="bg-white dark:bg-emerald-900/20 hover:shadow-2xl hover:shadow-emerald-900/10 dark:hover:shadow-black/40 transition-all duration-300 group">
                    <td class="px-8 py-6 rounded-l-[2.5rem] border-y border-l border-emerald-50 dark:border-emerald-800/50">
                        <div class="flex flex-col">
                            <span class="text-emerald-950 dark:text-white font-bold text-lg mb-1">{{ $arsip->surat->perihal }}</span>
                            <span class="bg-emerald-50 dark:bg-emerald-800/40 px-2 py-0.5 rounded text-emerald-700 dark:text-emerald-300 font-bold border border-emerald-100 dark:border-emerald-700 w-fit text-xs">{{ $arsip->surat->nomor_surat }}</span>
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

    {{-- Pagination --}}
    @if($arsipSurat->hasPages())
    <div class="mt-10">
        {{ $arsipSurat->links() }}
    </div>
    @endif
</div>
@endsection