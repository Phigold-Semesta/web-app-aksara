@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-end mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-emerald-950 tracking-tight">Manajemen Arsip</h1>
            <p class="text-emerald-600 font-medium mt-1">Monitoring lokasi penyimpanan dan masa retensi dokumen digital</p>
        </div>
        <a href="{{ route('petugas.manajemen_arsip.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-3 rounded-2xl font-bold transition-all shadow-lg shadow-emerald-200 flex items-center gap-2">
            <i class="fas fa-box-archive"></i> Catat Arsip Baru
        </a>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white rounded-[2rem] p-6 mb-8 shadow-sm border border-emerald-50 flex flex-wrap gap-4 items-center justify-between">
        <form action="{{ route('petugas.manajemen_arsip.index') }}" method="GET" class="flex gap-3 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari perihal atau nomor surat..." class="bg-emerald-50/50 border-none rounded-xl px-5 py-3 w-80 focus:ring-2 focus:ring-emerald-500 text-emerald-900 placeholder-emerald-300">
            <button type="submit" class="bg-emerald-100 text-emerald-700 px-5 py-3 rounded-xl font-bold hover:bg-emerald-200 transition-colors">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-separate border-spacing-y-4">
            <thead>
                <tr class="text-emerald-900/40 uppercase text-[11px] font-black tracking-[0.2em]">
                    <th class="px-8 py-2 text-left">Informasi Surat</th>
                    <th class="px-8 py-2 text-left">Lokasi Fisik</th>
                    <th class="px-8 py-2 text-center">Masa Retensi</th>
                    <th class="px-8 py-2 text-center">Status</th>
                    <th class="px-8 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($arsips as $arsip)
                <tr class="bg-white hover:shadow-xl hover:shadow-emerald-900/5 transition-all duration-300 group">
                    <td class="px-8 py-6 rounded-l-[2rem] border-y border-l border-emerald-50">
                        <div class="flex flex-col">
                            <span class="text-emerald-950 font-bold text-lg mb-1 group-hover:text-emerald-600 transition-colors">{{ $arsip->surat->perihal }}</span>
                            <div class="flex items-center gap-2 text-xs font-medium text-emerald-500/80">
                                <span class="bg-emerald-50 px-2 py-0.5 rounded">{{ $arsip->surat->nomor_surat }}</span>
                                <span>•</span>
                                <span>{{ \Carbon\Carbon::parse($arsip->tanggal_arsip)->format('d M Y') }}</span>
                            </div>
                        </div>
                    </td>
                    <td class="px-8 py-6 border-y border-emerald-50">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                                <i class="fas fa-map-location-dot"></i>
                            </div>
                            <span class="font-bold text-emerald-800">{{ $arsip->lokasi_fisik }}</span>
                        </div>
                    </td>
                    <td class="px-8 py-6 border-y border-emerald-50 text-center">
                        {{-- LOGIKA BARU: Menampilkan Tanggal Kadaluarsa yang Lengkap --}}
                        <span class="font-black text-emerald-950">{{ \Carbon\Carbon::parse($arsip->masa_retensi)->format('d M Y') }}</span>
                        <p class="text-[10px] text-emerald-400 font-bold uppercase tracking-tighter">
                            Kadaluarsa {{ \Carbon\Carbon::parse($arsip->masa_retensi)->diffForHumans() }}
                        </p>
                    </td>
                    <td class="px-8 py-6 border-y border-emerald-50 text-center">
                        @if($arsip->status_retensi == 'Aktif')
                            <span class="bg-emerald-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full shadow-lg shadow-emerald-200 uppercase">Aktif</span>
                        @else
                            <span class="bg-red-500 text-white text-[10px] font-black px-4 py-1.5 rounded-full shadow-lg shadow-red-200 uppercase">Inaktif</span>
                        @endif
                    </td>
                    <td class="px-8 py-6 rounded-r-[2rem] border-y border-r border-emerald-50 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('petugas.manajemen_arsip.show', $arsip->id_arsip) }}" class="p-3 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('petugas.manajemen_arsip.edit', $arsip->id_arsip) }}" class="p-3 bg-amber-50 text-amber-600 rounded-xl hover:bg-amber-500 hover:text-white transition-all">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('petugas.manajemen_arsip.destroy', $arsip->id_arsip) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Hapus data arsip ini?')" class="p-3 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $arsips->links() }}
    </div>
</div>
@endsection