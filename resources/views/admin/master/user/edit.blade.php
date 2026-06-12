@extends('layouts.app')

@section('title', 'Edit Data Pengguna - AKSARA')

@section('content')
<div class="p-4 md:p-6 space-y-6 animate__animated animate__fadeIn max-w-3xl mx-auto">

    {{-- Header Section --}}
    <div class="bg-gradient-to-r from-emerald-900 to-emerald-700 dark:from-emerald-950 dark:to-emerald-900 rounded-[2rem] p-8 text-white shadow-2xl relative overflow-hidden transition-all duration-300">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tight italic text-white">EDIT USER</h1>
                <p class="text-emerald-200 font-bold tracking-widest mt-1 uppercase text-xs">AKSARA - Sistem Informasi Digital LPSE Karawang</p>
            </div>
            <a href="{{ route('admin.master.user.index') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white px-5 py-2.5 rounded-xl font-black uppercase text-xs transition-all flex items-center gap-2 shrink-0 border border-white/10">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
    </div>

    {{-- Form Section --}}
    <div class="bg-white dark:bg-emerald-900/40 p-6 md:p-8 rounded-[2rem] shadow-xl border border-emerald-50 dark:border-emerald-800/50 transition-all duration-300">
        @php
            // Pastikan menggunakan kunci primer yang benar sesuai database Anda
            $userId = $user->id_user ?? $user->id;
        @endphp
        
        <form action="{{ route('admin.master.user.update', $userId) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Nama Lengkap --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-2 ml-1">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" required placeholder="Contoh: Ahmad Subagja" 
                        class="w-full bg-emerald-50 dark:bg-emerald-950 border-2 border-transparent focus:border-emerald-500 dark:border-emerald-800 rounded-xl px-4 py-3.5 text-sm font-semibold text-emerald-900 dark:text-white outline-none transition-all @error('nama_lengkap') border-red-500 @enderror">
                    @error('nama_lengkap') <span class="text-[10px] text-red-500 font-bold mt-1 ml-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Jabatan --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-2 ml-1">Jabatan</label>
                    <input type="text" name="jabatan" value="{{ old('jabatan', $user->jabatan) }}" required placeholder="Contoh: Staff IT / Kepala Sub Bagian" 
                        class="w-full bg-emerald-50 dark:bg-emerald-950 border-2 border-transparent focus:border-emerald-500 dark:border-emerald-800 rounded-xl px-4 py-3.5 text-sm font-semibold text-emerald-900 dark:text-white outline-none transition-all @error('jabatan') border-red-500 @enderror">
                    @error('jabatan') <span class="text-[10px] text-red-500 font-bold mt-1 ml-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Username --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-2 ml-1">Username</label>
                    <input type="text" name="username" value="{{ old('username', $user->username) }}" required placeholder="Contoh: ahmad_subagja" 
                        class="w-full bg-emerald-50 dark:bg-emerald-950 border-2 border-transparent focus:border-emerald-500 dark:border-emerald-800 rounded-xl px-4 py-3.5 text-sm font-mono text-emerald-900 dark:text-white outline-none transition-all @error('username') border-red-500 @enderror">
                    @error('username') <span class="text-[10px] text-red-500 font-bold mt-1 ml-1 block">{{ $message }}</span> @enderror
                </div>

                {{-- Role --}}
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-2 ml-1">Role Akses</label>
                    <select name="role" required class="w-full bg-emerald-50 dark:bg-emerald-950 border-2 border-transparent focus:border-emerald-500 dark:border-emerald-800 rounded-xl px-4 py-3.5 text-sm font-bold text-emerald-900 dark:text-white outline-none transition-all cursor-pointer">
                        <option value="petugas" {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>PETUGAS</option>
                        <option value="pimpinan" {{ old('role', $user->role) == 'pimpinan' ? 'selected' : '' }}>PIMPINAN</option>
                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>ADMIN</option>
                    </select>
                </div>

                {{-- Password --}}
                <div class="md:col-span-2">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-emerald-600 dark:text-emerald-400 mb-2 ml-1">
                        Password Baru <span class="text-[10px] font-normal lowercase italic text-emerald-500">(kosongkan jika tidak ingin diubah)</span>
                    </label>
                    <input type="password" name="password" placeholder="Isi hanya jika ingin ganti sandi" 
                        class="w-full bg-emerald-50 dark:bg-emerald-950 border-2 border-transparent focus:border-emerald-500 dark:border-emerald-800 rounded-xl px-4 py-3.5 text-sm text-emerald-900 dark:text-white outline-none transition-all @error('password') border-red-500 @enderror">
                    @error('password') <span class="text-[10px] text-red-500 font-bold mt-1 ml-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Submit Section --}}
            <div class="pt-6 border-t border-emerald-100 dark:border-emerald-800/50 flex items-center justify-end gap-3">
                <a href="{{ route('admin.master.user.index') }}" class="px-6 py-3 text-xs font-black uppercase tracking-wider text-emerald-700 dark:text-emerald-300 hover:bg-emerald-100 dark:hover:bg-emerald-800 rounded-xl transition-all">
                    Batal
                </a>
                <button type="submit" class="bg-emerald-900 dark:bg-emerald-600 text-white px-8 py-3 rounded-xl font-black uppercase text-xs hover:bg-emerald-800 dark:hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-900/20 flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection