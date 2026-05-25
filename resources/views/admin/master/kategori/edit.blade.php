@extends('layouts.app')

@section('title', 'Edit Kategori Surat')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="bg-white dark:bg-emerald-900 p-8 rounded-3xl shadow-sm border border-emerald-50 dark:border-emerald-800">
        <h2 class="text-xl font-black text-slate-800 dark:text-white uppercase mb-1">Edit Kategori</h2>
        <p class="text-sm text-slate-500 dark:text-emerald-300/70 mb-6">
            Perbarui informasi kategori: 
            <span class="font-bold text-[#008f5d]">{{ $kategori->nama_kategori ?? 'Kategori Tanpa Nama' }}</span>
        </p>

        <form action="{{ route('admin.master.kategori.update', $kategori->getKey()) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-slate-400 dark:text-emerald-400 uppercase mb-2">Kode Kategori</label>
                    <input type="text" name="kode_kategori" value="{{ old('kode_kategori', $kategori->kode_kategori) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-emerald-700 bg-slate-50 dark:bg-emerald-950 text-slate-800 dark:text-white focus:ring-2 focus:ring-[#008f5d] outline-none transition-all">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 dark:text-emerald-400 uppercase mb-2">Nama Kategori</label>
                    <input type="text" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-emerald-700 bg-slate-50 dark:bg-emerald-950 text-slate-800 dark:text-white focus:ring-2 focus:ring-[#008f5d] outline-none transition-all">
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-400 dark:text-emerald-400 uppercase mb-2">Keterangan (Opsional)</label>
                <textarea name="keterangan" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 dark:border-emerald-700 bg-slate-50 dark:bg-emerald-950 text-slate-800 dark:text-white focus:ring-2 focus:ring-[#008f5d] outline-none transition-all">{{ old('keterangan', $kategori->keterangan) }}</textarea>
            </div>

            <div class="flex items-center gap-3 pt-4">
                <a href="{{ route('admin.master.kategori.index') }}" class="px-6 py-3 rounded-xl bg-slate-100 dark:bg-emerald-800 text-slate-600 dark:text-emerald-200 font-bold text-sm hover:bg-slate-200 dark:hover:bg-emerald-700 transition-colors">Batal</a>
                <button type="submit" class="flex-1 px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-sm shadow-lg shadow-amber-600/20 transition-all">Perbarui Data</button>
            </div>
        </form>
    </div>
</div>
@endsection