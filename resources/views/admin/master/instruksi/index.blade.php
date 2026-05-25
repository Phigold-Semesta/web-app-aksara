@extends('layouts.app')

@section('title', 'Master Instruksi Pimpinan - AKSARA')

@section('content')
<div class="p-4 md:p-6 space-y-6 animate__animated animate__fadeIn">
    
    <div class="bg-gradient-to-r from-emerald-900 to-emerald-700 rounded-[2rem] p-8 text-white shadow-2xl relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-4xl font-black uppercase tracking-tight italic text-white">MASTER INSTRUKSI PIMPINAN</h1>
            <p class="text-emerald-300 font-bold tracking-widest mt-2 uppercase text-sm">AKSARA - Sistem Informasi Digital LPSE Karawang</p>
        </div>
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
    </div>

    <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-xl border border-emerald-50">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-xl font-black text-emerald-900 uppercase italic">Daftar Instruksi</h2>
            <a href="{{ route('admin.master.instruksi.create') }}" class="bg-emerald-900 text-emerald-100 px-6 py-3 rounded-xl font-black uppercase text-xs hover:bg-emerald-800 transition-all flex items-center gap-2 shadow-lg">
                <i class="fas fa-plus"></i> Tambah Instruksi
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-y-4">
                <thead>
                    <tr class="text-emerald-500 text-[10px] font-black uppercase tracking-widest">
                        <th class="px-6 py-3">Nama Instruksi</th>
                        <th class="px-6 py-3">Tanggal Input</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($instruksi as $item)
                    <tr class="bg-emerald-50/50 hover:bg-emerald-100 transition-all duration-300 rounded-2xl group shadow-sm">
                        <td class="px-6 py-4 font-bold text-emerald-900 rounded-l-2xl group-hover:text-emerald-700">{{ $item->nama_instruksi }}</td>
                        <td class="px-6 py-4 text-emerald-600 text-sm">{{ $item->created_at->format('d M Y') }}</td>
                        
                        <td class="px-6 py-4 text-center rounded-r-2xl">
                            <div class="flex justify-center items-center gap-3">
                                <a href="{{ route('admin.master.instruksi.edit', $item->id) }}" 
                                   class="bg-amber-100 text-amber-700 hover:bg-amber-500 hover:text-white transition-all duration-300 p-2.5 rounded-lg flex items-center justify-center shadow-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <form action="{{ route('admin.master.instruksi.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus instruksi ini?')">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-100 text-red-600 hover:bg-red-600 hover:text-white transition-all duration-300 p-2.5 rounded-lg flex items-center justify-center shadow-sm">
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
    </div>
</div>
@endsection