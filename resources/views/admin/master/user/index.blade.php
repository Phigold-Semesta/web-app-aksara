@extends('layouts.app')

@section('title', 'Manajemen User')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-emerald-900 p-6 rounded-3xl shadow-sm border border-emerald-50 dark:border-emerald-800 transition-colors">
        <div>
            <h1 class="text-2xl font-black text-slate-800 dark:text-white uppercase tracking-tight">Manajemen User</h1>
            <p class="text-sm text-slate-500 dark:text-emerald-300/70 mt-1">Mengelola data hak akses pengguna ke dalam sistem AKSARA LPSE Karawang.</p>
        </div>
        <a href="{{ route('admin.master.user.create') }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-[#008f5d] hover:bg-emerald-700 text-white font-bold text-sm shadow-lg shadow-emerald-600/20 transition-all shrink-0">
            <i class="fas fa-user-plus text-sm"></i>
            <span>Tambah User Baru</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-200 border border-emerald-200 dark:border-emerald-800 flex items-center gap-3">
            <i class="fas fa-circle-check text-lg text-[#008f5d]"></i>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-red-50 dark:bg-red-950 text-red-800 dark:text-red-200 border border-red-200 dark:border-red-900/50 space-y-1">
            <div class="flex items-center gap-3 font-bold text-sm">
                <i class="fas fa-circle-exclamation text-lg text-red-500"></i>
                <span>Gagal memproses data:</span>
            </div>
            <ul class="list-disc pl-8 text-xs space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white dark:bg-emerald-900 rounded-3xl shadow-sm border border-emerald-50 dark:border-emerald-800 transition-colors overflow-hidden">
        <div class="overflow-x-auto custom-scrollbar">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-emerald-800 bg-slate-50/50 dark:bg-emerald-950/20 text-slate-400 dark:text-emerald-400 text-[10px] font-black uppercase tracking-widest">
                        <th class="py-4 px-6 text-center w-16">No</th>
                        <th class="py-4 px-6">Avatar</th>
                        <th class="py-4 px-6">Username</th>
                        <th class="py-4 px-6">Nama Lengkap</th>
                        <th class="py-4 px-6">Role / Hak Akses</th>
                        <th class="py-4 px-6 text-center w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-emerald-800 text-sm text-slate-700 dark:text-emerald-100">
                    @forelse($users as $index => $user)
                        @php 
                            $userId = $user->id ?? $user->id_user ?? null; 
                        @endphp
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-emerald-950/10 transition-colors">
                            <td class="py-4 px-6 text-center font-bold text-slate-400 dark:text-emerald-500">
                                {{ $users->firstItem() + $index }}
                            </td>
                            <td class="py-4 px-6">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&background=008f5d&color=fff&bold=true" class="w-9 h-9 rounded-xl border border-slate-100 dark:border-emerald-700 shadow-sm" alt="Avatar">
                            </td>
                            <td class="py-4 px-6 font-mono font-bold text-slate-800 dark:text-white">{{ $user->username }}</td>
                            <td class="py-4 px-6 font-semibold">{{ $user->nama_lengkap }}</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                    @if($user->role === 'admin') bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300 border border-purple-200/50 dark:border-purple-900/50
                                    @elseif($user->role === 'pimpinan') bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200/50 dark:border-amber-900/50
                                    @else bg-emerald-50 text-[#008f5d] dark:bg-emerald-950/40 dark:text-emerald-300 border border-emerald-200/50 dark:border-emerald-900/50 @endif">
                                    <span class="w-1.5 h-1.5 rounded-full 
                                        @if($user->role === 'admin') bg-purple-500 
                                        @elseif($user->role === 'pimpinan') bg-amber-500 
                                        @else bg-emerald-500 @endif"></span>
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.master.user.show', $userId) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-emerald-50 text-[#008f5d] dark:bg-emerald-950/50 dark:text-emerald-400 hover:bg-[#008f5d] hover:text-white dark:hover:bg-emerald-500 dark:hover:text-white transition-all border border-emerald-100 dark:border-emerald-900/50 shadow-sm" title="Detail User">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>

                                    <a href="{{ route('admin.master.user.edit', $userId) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400 hover:bg-amber-500 hover:text-white dark:hover:bg-amber-500 dark:hover:text-white transition-all border border-amber-100 dark:border-amber-900/50 shadow-sm" title="Edit Data">
                                        <i class="fas fa-pen-to-square text-xs"></i>
                                    </a>
                                    
                                    @if(auth()->id() != $userId && $userId !== null)
                                        <form id="delete-form-{{ $userId }}" action="{{ route('admin.master.user.destroy', $userId) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDeleteUser({{ $userId }}, '{{ $user->nama_lengkap }}')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600 dark:bg-red-950/50 dark:text-red-400 hover:bg-red-500 hover:text-white dark:hover:bg-red-500 dark:hover:text-white transition-all border border-red-100 dark:border-red-900/50 shadow-sm" title="Hapus Data">
                                                <i class="fas fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    @else
                                        <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-100 text-slate-400 dark:bg-emerald-950/30 dark:text-emerald-800 cursor-not-allowed border border-slate-200/50 dark:border-emerald-900" title="Sesi Aktif Tidak Bisa Dihapus" disabled>
                                            <i class="fas fa-lock text-xs"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 px-6 text-center text-slate-400 dark:text-emerald-600 font-medium">
                                <i class="fas fa-folder-open text-4xl mb-3 block"></i>
                                Belum ada data pengguna dalam sistem ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-5 border-t border-slate-100 dark:border-emerald-800 bg-slate-50/30 dark:bg-emerald-950/10">
                {{ $users->links() }}
            </div>
        @endif
    </div>

</div>

<script>
    function confirmDeleteUser(id, name) {
        Swal.fire({
            title: 'Hapus Pengguna?',
            text: "User bernama '" + name + "' akan dihapus permanen dan kehilangan akses ke sistem.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'YA, HAPUS',
            cancelButtonText: 'BATAL',
            reverseButtons: true,
            background: document.documentElement.classList.contains('dark') ? '#064e3b' : '#fff',
            color: document.documentElement.classList.contains('dark') ? '#ecfdf5' : '#1e293b',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>
@endsection