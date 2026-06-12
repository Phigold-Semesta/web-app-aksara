@extends('layouts.app')

@section('title', 'Manajemen User - AKSARA')

@section('content')
<div class="p-4 md:p-6 space-y-6 animate__animated animate__fadeIn">
    
    {{-- Header Section --}}
    <div class="bg-gradient-to-r from-emerald-900 to-emerald-700 dark:from-emerald-950 dark:to-emerald-900 rounded-[2rem] p-8 text-white shadow-2xl relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-4xl font-black uppercase tracking-tight italic text-white">MANAJEMEN USER</h1>
            <p class="text-emerald-200 font-bold tracking-widest mt-2 uppercase text-sm">AKSARA - Sistem Informasi Digital LPSE Karawang</p>
        </div>
        <div class="absolute -top-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full"></div>
    </div>

    {{-- Content Section --}}
    <div class="bg-white dark:bg-emerald-900/40 p-6 md:p-8 rounded-[2rem] shadow-xl border border-emerald-50 dark:border-emerald-800/50">
        
        {{-- Toolbar --}}
        <div class="flex flex-col lg:flex-row justify-between items-center mb-8 gap-4">
            <h2 class="text-xl font-black text-emerald-950 dark:text-white uppercase italic">Daftar Pengguna</h2>
            
            <div class="flex flex-wrap items-center gap-3 w-full lg:w-auto justify-end">
                <form action="{{ route('admin.master.user.index') }}" method="GET" class="flex flex-wrap gap-2">
                    <input type="text" name="search" placeholder="Cari..." value="{{ request('search') }}" 
                        class="bg-emerald-50 dark:bg-emerald-950 border-emerald-200 dark:border-emerald-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none w-full sm:w-40">
                    
                    <select name="per_page" class="bg-emerald-50 dark:bg-emerald-950 border-emerald-200 dark:border-emerald-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="5" {{ request('per_page') == '5' ? 'selected' : '' }}>5 Baris</option>
                        <option value="10" {{ request('per_page') == '10' ? 'selected' : '' }}>10 Baris</option>
                        <option value="25" {{ request('per_page') == '25' ? 'selected' : '' }}>25 Baris</option>
                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>Semua</option>
                    </select>

                    <select name="role" class="bg-emerald-50 dark:bg-emerald-950 border-emerald-200 dark:border-emerald-700 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pimpinan" {{ request('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                        <option value="petugas" {{ request('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                    </select>

                    <button type="submit" class="bg-emerald-700 text-white px-5 py-3 rounded-xl font-black text-xs hover:bg-emerald-800 transition-all shadow-lg">
                        FILTER
                    </button>
                </form>

                <a href="{{ route('admin.master.user.create') }}" class="bg-emerald-700 text-white px-6 py-3 rounded-xl font-black uppercase text-xs hover:bg-emerald-800 transition-all flex items-center gap-2 shadow-lg shrink-0">
                    <i class="fas fa-user-plus"></i> Tambah User
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-y-4">
                <thead>
                    <tr class="text-emerald-500 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest">
                        <th class="px-6 py-3 text-center w-16">No</th>
                        <th class="px-6 py-3">Avatar</th>
                        <th class="px-6 py-3">Username</th>
                        <th class="px-6 py-3">Nama Lengkap</th>
                        <th class="px-6 py-3">Jabatan</th>
                        <th class="px-6 py-3">Role</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    @php 
                        $userId = $user->id ?? $user->id_user ?? null; 
                        // Perbaikan warna pimpinan menjadi kuning ke-oranyean
                        $roleColors = [
                            'petugas' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-800 dark:text-emerald-200',
                            'admin' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
                            'pimpinan' => 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200'
                        ];
                        $badgeClass = $roleColors[$user->role] ?? 'bg-slate-100 text-slate-800';
                    @endphp
                    <tr class="bg-emerald-50/50 dark:bg-emerald-950/30 hover:bg-emerald-100 dark:hover:bg-emerald-900/50 transition-all duration-300 rounded-2xl shadow-sm">
                        <td class="px-6 py-4 font-black text-emerald-600 dark:text-emerald-400 text-center rounded-l-2xl">{{ $users->firstItem() + $index }}</td>
                        <td class="px-6 py-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&background=008f5d&color=fff&bold=true" class="w-10 h-10 rounded-xl shadow-sm border border-emerald-100 dark:border-emerald-800" alt="Avatar">
                        </td>
                        <td class="px-6 py-4 font-bold text-emerald-900 dark:text-emerald-100">{{ $user->username }}</td>
                        <td class="px-6 py-4 font-semibold text-emerald-800 dark:text-emerald-300">{{ $user->nama_lengkap }}</td>
                        <td class="px-6 py-4 font-medium text-emerald-700 dark:text-emerald-400 text-sm">{{ $user->jabatan ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $badgeClass }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center rounded-r-2xl">
                            <div class="flex justify-center items-center gap-2">
                                <a href="{{ route('admin.master.user.show', $userId) }}" class="bg-emerald-100 text-emerald-700 p-2.5 rounded-lg hover:bg-emerald-500 hover:text-white transition-all"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('admin.master.user.edit', $userId) }}" class="bg-amber-100 text-amber-700 p-2.5 rounded-lg hover:bg-amber-500 hover:text-white transition-all"><i class="fas fa-pen-to-square"></i></a>
                                
                                @if(auth()->id() != $userId)
                                <form id="delete-form-{{ $userId }}" action="{{ route('admin.master.user.destroy', $userId) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="confirmDeleteUser('{{ $userId }}', '{{ $user->nama_lengkap }}')" class="bg-red-100 text-red-600 p-2.5 rounded-lg hover:bg-red-600 hover:text-white transition-all">
                                        <i class="fas fa-trash-can"></i>
                                    </button>
                                </form>
                                @else
                                <button class="bg-slate-200 text-slate-500 p-2.5 rounded-lg cursor-not-allowed" title="User Sedang Aktif">
                                    <i class="fas fa-lock"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-emerald-400 font-bold italic">Data tidak ditemukan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6 pt-6 border-t border-emerald-50 dark:border-emerald-800/50">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'BERHASIL!',
            text: "{{ session('success') }}",
            confirmButtonColor: '#065f46',
            confirmButtonText: 'OK',
            customClass: { popup: 'rounded-[2rem]' }
        });
    @endif

    function confirmDeleteUser(id, name) {
        Swal.fire({
            icon: 'warning',
            title: 'HAPUS PENGGUNA?',
            text: "User '" + name + "' akan dihapus permanen.",
            showCancelButton: true,
            confirmButtonText: 'YA, HAPUS',
            cancelButtonText: 'BATAL',
            reverseButtons: true, 
            buttonsStyling: false,
            customClass: {
                popup: 'rounded-[2rem] p-6',
                confirmButton: 'bg-red-500 text-white px-8 py-3 rounded-xl font-black uppercase text-sm hover:bg-red-600 transition-all mx-2 shadow-lg',
                cancelButton: 'bg-slate-600 text-white px-8 py-3 rounded-xl font-black uppercase text-sm hover:bg-slate-700 transition-all mx-2 shadow-lg',
                title: 'text-2xl font-black text-slate-800 uppercase italic'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection