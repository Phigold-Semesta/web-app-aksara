@extends('layouts.app')

@section('title', 'Edit Data Pengguna')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-emerald-900 p-6 rounded-3xl shadow-sm border border-emerald-50 dark:border-emerald-800 transition-colors">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight flex items-center gap-2">
                <i class="fas fa-user-gear text-amber-500"></i> Edit Pengguna
            </h1>
            <p class="text-sm text-slate-500 dark:text-emerald-300/70 mt-1">Mengubah rincian data akun dari pengguna sistem.</p>
        </div>
        <a href="{{ route('admin.master.user.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-emerald-950 dark:hover:bg-emerald-900 text-slate-600 dark:text-emerald-300 font-bold text-sm transition-all shrink-0">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Kembali</span>
        </a>
    </div>

    <div class="bg-white dark:bg-emerald-900 rounded-3xl shadow-sm border border-emerald-50 dark:border-emerald-800 transition-colors overflow-hidden">
        @php
            $userId = $user->id ?? $user->id_user;
        @endphp
        <form action="{{ route('admin.master.user.update', $userId) }}" method="POST" class="p-6 sm:p-8 space-y-5">
            @csrf
            @method('PUT')
            
            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-500 dark:text-emerald-400 mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-emerald-950 border border-slate-200 dark:border-emerald-800/80 focus:outline-none focus:border-[#008f5d] dark:focus:border-emerald-500 text-sm font-semibold text-slate-800 dark:text-white transition-all @error('nama_lengkap') border-red-500 @enderror">
                @error('nama_lengkap') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-500 dark:text-emerald-400 mb-1.5">Username</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-emerald-950 border border-slate-200 dark:border-emerald-800/80 focus:outline-none focus:border-[#008f5d] dark:focus:border-emerald-500 text-sm font-mono text-slate-800 dark:text-white transition-all @error('username') border-red-500 @enderror">
                @error('username') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-500 dark:text-emerald-400 mb-1.5">
                    Password Baru <span class="text-[10px] text-slate-400 dark:text-emerald-400/50 font-normal lowercase italic">(kosongkan jika tidak ingin diubah)</span>
                </label>
                <input type="password" name="password" placeholder="Isi hanya jika ingin ganti sandi" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-emerald-950 border border-slate-200 dark:border-emerald-800/80 focus:outline-none focus:border-[#008f5d] dark:focus:border-emerald-500 text-sm text-slate-800 dark:text-white transition-all @error('password') border-red-500 @enderror">
                @error('password') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-wider text-slate-500 dark:text-emerald-400 mb-1.5">Role Akses</label>
                <select name="role" required class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-emerald-950 border border-slate-200 dark:border-emerald-800/80 focus:outline-none focus:border-[#008f5d] dark:focus:border-emerald-500 text-sm font-bold text-slate-800 dark:text-white transition-all">
                    <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>PETUGAS</option>
                    <option value="pimpinan" {{ old('role', $user->role) == 'pimpinan' ? 'selected' : '' }}>PIMPINAN</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>ADMIN</option>
                </select>
                @error('role') <span class="text-xs text-red-500 font-semibold mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-emerald-800/50 mt-6">
                <a href="{{ route('admin.master.user.index') }}" class="px-5 py-3 text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-emerald-300 rounded-xl hover:bg-slate-100 dark:hover:bg-emerald-950 transition-all">Batal</a>
                <button type="submit" class="px-6 py-3 text-xs font-black uppercase tracking-wider text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow-lg shadow-amber-500/10 transition-all">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection