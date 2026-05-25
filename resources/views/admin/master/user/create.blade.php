@extends('layouts.app')

@section('title', 'Tambah User Baru')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-emerald-900 p-6 rounded-3xl shadow-sm border border-emerald-50 dark:border-emerald-800 transition-colors">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight flex items-center gap-2">
                <i class="fas fa-user-plus text-[#008f5d]"></i> Tambah User Baru
            </h1>
            <p class="text-sm text-slate-500 dark:text-emerald-300/70 mt-1">Daatarkan pengguna baru ke dalam sistem AKSARA LPSE Karawang.</p>
        </div>
        <a href="{{ route('admin.master.user.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-emerald-950 dark:hover:bg-emerald-900 text-slate-600 dark:text-emerald-300 font-bold text-sm transition-all shrink-0">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white dark:bg-emerald-900 rounded-3xl shadow-sm border border-emerald-50 dark:border-emerald-800 transition-colors overflow-hidden">
        <form action="{{ route('admin.master.user.store') }}" method="POST" class="p-6 sm:p-8 space-y-5">
            @csrf
            
            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-500 dark:text-emerald-400 mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required placeholder="Contoh: Ahmad Subagja" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-emerald-950 border border-slate-200 dark:border-emerald-800/80 focus:outline-none focus:border-[#008f5d] dark:focus:border-emerald-500 text-sm font-semibold text-slate-800 dark:text-white transition-all @error('nama_lengkap') border-red-500 @enderror">
                @error('nama_lengkap') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-500 dark:text-emerald-400 mb-1.5">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required placeholder="Contoh: ahmad_subagja" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-emerald-950 border border-slate-200 dark:border-emerald-800/80 focus:outline-none focus:border-[#008f5d] dark:focus:border-emerald-500 text-sm font-mono text-slate-800 dark:text-white transition-all @error('username') border-red-500 @enderror">
                @error('username') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-500 dark:text-emerald-400 mb-1.5">Password</label>
                <input type="password" name="password" required placeholder="Minimal 6 karakter" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-emerald-950 border border-slate-200 dark:border-emerald-800/80 focus:outline-none focus:border-[#008f5d] dark:focus:border-emerald-500 text-sm text-slate-800 dark:text-white transition-all @error('password') border-red-500 @enderror">
                @error('password') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-500 dark:text-emerald-400 mb-1.5">Role Akses</label>
                <select name="role" required class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-emerald-950 border border-slate-200 dark:border-emerald-800/80 focus:outline-none focus:border-[#008f5d] dark:focus:border-emerald-500 text-sm font-bold text-slate-800 dark:text-white transition-all">
                    <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>PETUGAS</option>
                    <option value="pimpinan" {{ old('role') == 'pimpinan' ? 'selected' : '' }}>PIMPINAN</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>ADMIN</option>
                </select>
                @error('role') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-emerald-800/50 mt-6">
                <a href="{{ route('admin.master.user.index') }}" class="px-5 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-emerald-300 rounded-xl hover:bg-slate-100 dark:hover:bg-emerald-950 transition-all">Batal</a>
                <button type="submit" class="px-6 py-3 text-xs font-black uppercase tracking-wider text-white bg-[#008f5d] hover:bg-emerald-700 rounded-xl shadow-lg shadow-emerald-600/10 transition-all">
                    Simpan User Baru
                </button>
            </div>
        </form>
    </div>

</div>
@endsection