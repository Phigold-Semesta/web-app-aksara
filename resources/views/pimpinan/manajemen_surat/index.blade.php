@extends('layouts.app')

@section('title', 'Manajemen Surat')

@section('content')
<div class="space-y-10">
    {{-- Header Section --}}
    <div class="flex justify-between items-end border-b border-emerald-100 pb-6">
        <div>
            <p class="text-emerald-600 font-black text-[10px] uppercase tracking-[0.3em]">Command Center</p>
            <h1 class="text-3xl font-black text-slate-800 uppercase italic">Manajemen Surat</h1>
        </div>
        <div class="text-right">
            <p class="text-[10px] font-black text-slate-400 uppercase">Total Perlu Ditinjau</p>
            <h2 class="text-2xl font-black text-emerald-600">{{ $suratMasuk->count() }}</h2>
        </div>
    </div>

    {{-- SECTION 1: SURAT MASUK (Tugas Disposisi) --}}
    <section>
        <div class="flex items-center gap-3 mb-6">
            <span class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></span>
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-700">Butuh Instruksi Disposisi</h3>
        </div>
        
        <div class="bg-white p-6 rounded-3xl border border-emerald-50 shadow-sm">
            <table class="w-full text-left">
                <thead class="text-slate-400 text-[10px] font-black uppercase tracking-widest">
                    <tr>
                        <th class="p-4">No. Surat</th>
                        <th class="p-4">Perihal</th>
                        <th class="p-4">Tanggal Masuk</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-50">
                    @forelse($suratMasuk as $surat)
                    <tr class="hover:bg-emerald-50/30 transition-colors">
                        <td class="p-4 font-bold text-sm">{{ $surat->nomor_surat }}</td>
                        <td class="p-4 text-sm">{{ $surat->perihal }}</td>
                        <td class="p-4 text-sm text-slate-500">{{ $surat->created_at->format('d M Y') }}</td>
                        <td class="p-4 text-center">
                            <a href="{{ route('pimpinan.manajemen-surat.show', $surat->id) }}" 
                               class="inline-flex items-center gap-2 bg-emerald-600 text-white px-4 py-2 rounded-xl text-[10px] font-black hover:bg-emerald-700 transition">
                               <i class="fas fa-file-signature"></i> TINJAU
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-slate-400 font-bold italic text-sm">Tidak ada surat yang perlu ditinjau.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    {{-- SECTION 2: RIWAYAT DISPOSISI --}}
    <section>
        <h3 class="text-xs font-black uppercase tracking-widest text-slate-700 mb-6">Riwayat Tindakan</h3>
        
        <div class="bg-white p-6 rounded-3xl border border-emerald-50 shadow-sm">
            <table class="w-full text-left">
                <thead class="text-slate-400 text-[10px] font-black uppercase tracking-widest">
                    <tr>
                        <th class="p-4">Surat</th>
                        <th class="p-4">Instruksi Pimpinan</th>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-emerald-50">
                    @forelse($riwayat as $r)
                    <tr class="hover:bg-emerald-50/30 transition-colors">
                        <td class="p-4 font-bold text-sm">{{ $r->surat->nomor_surat }}</td>
                        <td class="p-4 text-sm">{{ $r->instruksi->nama_instruksi ?? 'N/A' }}</td>
                        <td class="p-4 text-sm text-slate-500">{{ $r->created_at->format('d M Y') }}</td>
                        <td class="p-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('pimpinan.manajemen-surat.show', $r->surat->id) }}" 
                                   class="p-2.5 bg-emerald-50 text-emerald-600 rounded-xl hover:bg-emerald-600 hover:text-white transition-all" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('pimpinan.manajemen-surat.destroy_riwayat', $r->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition-all" 
                                            onclick="return confirm('Apakah Anda yakin ingin menghapus riwayat ini?')" title="Hapus Riwayat">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center text-slate-400 font-bold italic text-sm">Belum ada riwayat tindakan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection