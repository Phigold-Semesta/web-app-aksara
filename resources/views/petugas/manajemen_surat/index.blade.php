@extends('layouts.app')

@section('content')
<div class="p-8">
    <div class="flex justify-between items-end mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-emerald-950 tracking-tight">Manajemen Surat</h1>
            <p class="text-emerald-600 font-medium mt-1">Digitalisasi dan Pengarsipan Surat LPSE Karawang</p>
        </div>
        <a href="{{ route('petugas.manajemen_surat.create') }}" 
           class="bg-emerald-600 hover:bg-emerald-700 text-white px-8 py-3.5 rounded-2xl font-bold shadow-lg shadow-emerald-200 transition-all flex items-center gap-3 transform hover:-translate-y-1">
            <i class="fas fa-plus-circle text-lg"></i>
            INPUT SURAT BARU
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 p-4 bg-emerald-100 border-l-4 border-emerald-500 text-emerald-700 rounded-xl shadow-sm">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    </div>
    @endif

    <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 overflow-hidden border border-emerald-50">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-emerald-50/50 border-b border-emerald-100">
                        <th class="px-8 py-6 text-emerald-900 font-black uppercase text-xs tracking-widest">No. Agenda</th>
                        <th class="px-8 py-6 text-emerald-900 font-black uppercase text-xs tracking-widest">Data Surat</th>
                        <th class="px-8 py-6 text-emerald-900 font-black uppercase text-xs tracking-widest text-center">Kategori</th>
                        <th class="px-8 py-6 text-emerald-900 font-black uppercase text-xs tracking-widest text-center">Status</th>
                        <th class="px-8 py-6 text-emerald-900 font-black uppercase text-xs tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-50/80">
                    @forelse($surats as $item)
                    <tr class="hover:bg-emerald-50/30 transition-colors group">
                        <td class="px-8 py-6">
                            {{-- Perbaikan: Gunakan id_surat sesuai kolom database --}}
                            <span class="text-emerald-950 font-bold block">#{{ $item->id_surat }}</span>
                            <span class="text-emerald-400 text-[10px] font-bold">{{ $item->created_at->format('d M Y') }}</span>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-emerald-950 font-bold text-base group-hover:text-emerald-600 transition-colors">{{ $item->perihal }}</span>
                                <span class="text-emerald-500/80 text-sm italic">{{ $item->asal_instansi }}</span>
                                <span class="text-emerald-400 text-xs mt-1">No: {{ $item->nomor_surat }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <span class="px-4 py-1.5 bg-emerald-100 text-emerald-700 rounded-lg text-[11px] font-black uppercase">
                                {{ $item->kategori->nama_kategori ?? 'Umum' }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($item->status == 'pending')
                                <span class="text-orange-500 font-bold text-xs uppercase flex items-center justify-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-orange-500 rounded-full animate-pulse"></span> Diperiksa
                                </span>
                            @else
                                <span class="text-blue-500 font-bold text-xs uppercase flex items-center justify-center gap-1">
                                    <i class="fas fa-paper-plane text-[10px]"></i> {{ $item->status }}
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex items-center justify-center gap-3">
                                {{-- Perbaikan: Parameter id_surat untuk route show --}}
                                <a href="{{ route('petugas.manajemen_surat.show', $item->id_surat) }}" class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all shadow-sm" title="Detail">
                                    <i class="fas fa-eye text-sm"></i>
                                </a>
                                
                                {{-- Perbaikan: Gunakan route yang benar sesuai controller --}}
                                <form action="{{ route('petugas.teruskan_pimpinan', $item->id_surat) }}" method="POST">
                                    @csrf 
                                    @method('PATCH')
                                    <button type="submit" class="p-2.5 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all shadow-sm" onclick="return confirm('Teruskan ke Pimpinan?')">
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
                                <i class="fas fa-folder-open text-5xl text-emerald-100 mb-4"></i>
                                <p class="text-emerald-400 italic font-medium">Belum ada data surat yang diarsipkan hari ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Footer Tabel & Pagination --}}
        @if($surats->hasPages())
        <div class="px-8 py-6 bg-emerald-50/30 border-t border-emerald-50">
            {{ $surats->links() }}
        </div>
        @endif
    </div>
</div>
@endsection