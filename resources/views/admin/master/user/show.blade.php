@extends('layouts.app')

@section('title', 'Detail Profil Pengguna - AKSARA')

@section('content')
<div class="p-4 md:p-6 space-y-6 animate__animated animate__fadeIn max-w-3xl mx-auto">

    {{-- Header Section --}}
    <div class="bg-gradient-to-r from-emerald-900 to-emerald-700 dark:from-emerald-950 dark:to-emerald-900 rounded-[2rem] p-8 text-white shadow-2xl relative overflow-hidden transition-all duration-300">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tight italic text-white">DETAIL USER</h1>
                <p class="text-emerald-200 font-bold tracking-widest mt-1 uppercase text-xs">AKSARA - Sistem Informasi Digital LPSE Karawang</p>
            </div>
            <a href="{{ route('admin.master.user.index') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-md text-white px-5 py-2.5 rounded-xl font-black uppercase text-xs transition-all flex items-center gap-2 shrink-0 border border-white/10">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
    </div>

    {{-- Content Section --}}
    <div class="bg-white dark:bg-emerald-900/40 p-6 md:p-8 rounded-[2rem] shadow-xl border border-emerald-50 dark:border-emerald-800/50 transition-all duration-300">
        
        {{-- Profile Avatar Section --}}
        <div class="flex flex-col items-center text-center pb-8 border-b border-emerald-100 dark:border-emerald-800/50">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&background=008f5d&color=fff&bold=true&size=256" 
                class="w-32 h-32 rounded-[2rem] border-4 border-emerald-100 dark:border-emerald-800 shadow-2xl mb-6 transition-transform hover:scale-105" alt="Avatar">
            
            <h2 class="text-2xl font-black text-emerald-950 dark:text-white uppercase italic">{{ $user->nama_lengkap }}</h2>
            <p class="text-sm font-mono font-bold text-emerald-600 dark:text-emerald-400 mt-1 tracking-widest">{{ '@' . strtoupper($user->username) }}</p>
            
            <div class="mt-4">
                <span class="inline-flex items-center gap-2 px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest
                    @if($user->role === 'admin') bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200
                    @elseif($user->role === 'pimpinan') bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-200
                    @else bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-200 @endif">
                    <i class="fas fa-user-shield"></i> {{ $user->role }}
                </span>
            </div>
        </div>

        {{-- Detail Grid --}}
        <div class="mt-8 space-y-2">
            @php $fields = [
                ['label' => 'User ID', 'value' => $user->id ?? $user->id_user],
                ['label' => 'Nama Lengkap', 'value' => $user->nama_lengkap],
                ['label' => 'Jabatan', 'value' => $user->jabatan ?? '-'],
                ['label' => 'Username', 'value' => $user->username],
                ['label' => 'Dibuat Pada', 'value' => $user->created_at ? $user->created_at->translatedFormat('d F Y, H:i') : 'Bawaan Sistem']
            ];
            @endphp

            @foreach($fields as $field)
            <div class="grid grid-cols-1 md:grid-cols-3 py-4 border-b border-emerald-50 dark:border-emerald-800/50 hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20 px-4 rounded-xl transition-all">
                <span class="text-[10px] font-black uppercase tracking-widest text-emerald-500 dark:text-emerald-400">{{ $field['label'] }}</span>
                <span class="col-span-2 font-bold text-emerald-900 dark:text-white text-sm">{{ $field['value'] }}</span>
            </div>
            @endforeach
        </div>

        {{-- Action Button --}}
        <div class="mt-8 pt-6 border-t border-emerald-100 dark:border-emerald-800/50 flex justify-end">
            @php $userId = $user->id ?? $user->id_user; @endphp
            <a href="{{ route('admin.master.user.edit', $userId) }}" class="bg-emerald-900 dark:bg-emerald-600 text-white px-8 py-3 rounded-xl font-black uppercase text-xs hover:bg-emerald-800 dark:hover:bg-emerald-500 transition-all shadow-lg shadow-emerald-900/20 flex items-center gap-2">
                <i class="fas fa-pen-to-square"></i> Ubah Data
            </a>
        </div>
    </div>
</div>
@endsection