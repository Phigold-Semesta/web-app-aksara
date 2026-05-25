@extends('layouts.app')

@section('title', 'Detail Profil Pengguna')

@section('content')
<div class="space-y-6 max-w-2xl mx-auto">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-emerald-900 p-6 rounded-3xl shadow-sm border border-emerald-50 dark:border-emerald-800 transition-colors">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight flex items-center gap-2">
                <i class="fas fa-address-card text-emerald-500"></i> Detail Pengguna
            </h1>
            <p class="text-sm text-slate-500 dark:text-emerald-300/70 mt-1">Informasi lengkap hak akses serta profil identitas pengguna.</p>
        </div>
        <a href="{{ route('admin.master.user.index') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-emerald-950 dark:hover:bg-emerald-900 text-slate-600 dark:text-emerald-300 font-bold text-sm transition-all shrink-0">
            <i class="fas fa-arrow-left text-xs"></i>
            <span>Kembali ke List</span>
        </a>
    </div>

    <div class="bg-white dark:bg-emerald-900 rounded-3xl shadow-sm border border-emerald-50 dark:border-emerald-800 transition-colors overflow-hidden p-6 sm:p-8">
        <div class="flex flex-col items-center text-center pb-6 border-b border-slate-100 dark:border-emerald-800/50">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&background=008f5d&color=fff&bold=true&size=128" class="w-24 h-24 rounded-2xl border-2 border-emerald-500 shadow-md mb-4" alt="Avatar">
            
            <h2 class="text-xl font-bold text-slate-800 dark:text-white">{{ $user->nama_lengkap }}</h2>
            <p class="text-sm font-mono text-slate-400 dark:text-emerald-400/70 mt-0.5">{{ '@' . $user->username }}</p>
            
            <div class="mt-3">
                <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-wider
                    @if($user->role === 'admin') bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300 border border-purple-200/50 dark:border-purple-900/50
                    @elseif($user->role === 'pimpinan') bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200/50 dark:border-amber-900/50
                    @else bg-emerald-50 text-[#008f5d] dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-900/50 @endif">
                    <span class="w-2 h-2 rounded-full @if($user->role === 'admin') bg-purple-500 @elseif($user->role === 'pimpinan') bg-amber-500 @else bg-emerald-500 @endif"></span>
                    {{ $user->role }}
                </span>
            </div>
        </div>

        <div class="mt-6 space-y-4 text-sm">
            <div class="grid grid-cols-3 py-2 border-b border-slate-50 dark:border-emerald-800/20">
                <span class="text-slate-400 dark:text-emerald-400 font-bold uppercase tracking-wide text-xs">User ID</span>
                <span class="col-span-2 font-mono font-bold text-slate-700 dark:text-slate-300">{{ $user->id ?? $user->id_user }}</span>
            </div>
            <div class="grid grid-cols-3 py-2 border-b border-slate-50 dark:border-emerald-800/20">
                <span class="text-slate-400 dark:text-emerald-400 font-bold uppercase tracking-wide text-xs">Nama Lengkap</span>
                <span class="col-span-2 font-semibold text-slate-800 dark:text-white">{{ $user->nama_lengkap }}</span>
            </div>
            <div class="grid grid-cols-3 py-2 border-b border-slate-50 dark:border-emerald-800/20">
                <span class="text-slate-400 dark:text-emerald-400 font-bold uppercase tracking-wide text-xs">Nama Pengguna</span>
                <span class="col-span-2 font-mono font-semibold text-slate-800 dark:text-white">{{ $user->username }}</span>
            </div>
            <div class="grid grid-cols-3 py-2 border-b border-slate-50 dark:border-emerald-800/20">
                <span class="text-slate-400 dark:text-emerald-400 font-bold uppercase tracking-wide text-xs">Dibuat Pada</span>
                <span class="col-span-2 font-medium text-slate-700 dark:text-slate-300">
                    {{ $user->created_at ? $user->created_at->translatedFormat('d F Y (H:i)') : 'Bawaan Sistem / Seeding' }}
                </span>
            </div>
        </div>

        <div class="mt-8 pt-4 flex gap-2 justify-end border-t border-slate-100 dark:border-emerald-800/50">
            @php
                $userId = $user->id ?? $user->id_user;
            @endphp
            <a href="{{ route('admin.master.user.edit', $userId) }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs uppercase tracking-wider shadow-md transition-all">
                <i class="fas fa-pen-to-square"></i>
                <span>Ubah Data</span>
            </a>
        </div>
    </div>

</div>
@endsection