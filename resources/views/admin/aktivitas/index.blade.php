{{-- resources/views/admin/aktivitas/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Audit Log Sistem')

@section('content')

<div class="p-2 md:p-4 space-y-6 animate__animated animate__fadeIn">

    {{-- Header --}}
    <div class="relative overflow-hidden bg-[#006b43] rounded-[2rem] p-6 md:p-10 text-white shadow-2xl border border-emerald-400/20">

        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">

            <div class="space-y-2 text-center md:text-left">

                <span class="px-3 py-1 bg-white/20 text-emerald-200 rounded-lg text-[10px] font-black uppercase tracking-widest italic">
                    Keamanan & Integritas Data
                </span>

                <h1 class="text-3xl md:text-5xl font-black tracking-tighter leading-none uppercase italic mt-1">
                    MONITORING AUDIT LOG
                </h1>

                <p class="text-emerald-200 text-xs md:text-sm font-bold uppercase tracking-[0.15em]">
                    Rekam Jejak Digital dan Pengawasan Aktivitas Sistem AKSARA
                </p>

            </div>

            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl text-center shadow-xl w-full md:w-auto">

                <p class="text-[9px] font-black uppercase text-emerald-300">
                    Status Pengawasan
                </p>

                <p class="text-xs font-black uppercase tracking-tight flex items-center justify-center gap-2 mt-0.5">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    Sistem Terproteksi
                </p>

            </div>

        </div>

        <div class="absolute top-0 right-0 p-4 opacity-5 text-[15rem] pointer-events-none text-white font-black">
            <i class="fas fa-fingerprint"></i>
        </div>

    </div>

    {{-- Content --}}
    <div class="bg-white dark:bg-slate-900 p-6 rounded-[2rem] shadow-xl border border-slate-50 dark:border-slate-800">

        {{-- Filter --}}
        <form method="GET" action="{{ url()->current() }}">

            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6 bg-slate-50 dark:bg-slate-800/50 p-4 rounded-2xl items-end">

                <div>
                    <label class="text-[9px] font-black uppercase text-slate-400 ml-2">
                        Mulai Tanggal
                    </label>

                    <input
                        type="date"
                        name="start_date"
                        value="{{ request('start_date') }}"
                        class="w-full bg-white dark:bg-slate-800 rounded-xl text-xs font-bold p-3 border border-slate-200 dark:border-slate-700">
                </div>

                <div>
                    <label class="text-[9px] font-black uppercase text-slate-400 ml-2">
                        Sampai Tanggal
                    </label>

                    <input
                        type="date"
                        name="end_date"
                        value="{{ request('end_date') }}"
                        class="w-full bg-white dark:bg-slate-800 rounded-xl text-xs font-bold p-3 border border-slate-200 dark:border-slate-700">
                </div>

                <div>
                    <label class="text-[9px] font-black uppercase text-slate-400 ml-2">
                        Tampilkan
                    </label>

                    <select
                        name="per_page"
                        onchange="this.form.submit()"
                        class="w-full bg-white dark:bg-slate-800 rounded-xl text-xs font-bold p-3 border border-slate-200 dark:border-slate-700">

                        <option value="5" {{ request('per_page',5) == 5 ? 'selected' : '' }}>
                            5 Baris
                        </option>

                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>
                            10 Baris
                        </option>

                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>
                            25 Baris
                        </option>

                        <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>
                            Semua Data
                        </option>

                    </select>
                </div>

                <div>
                    <label class="text-[9px] font-black uppercase text-slate-400 ml-2">
                        Cari Aktivitas / User
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Cari aktivitas atau user..."
                        class="w-full bg-white dark:bg-slate-800 rounded-xl text-xs font-bold p-3 border border-slate-200 dark:border-slate-700">
                </div>

                <div class="flex gap-2">

                    <button
                        type="submit"
                        class="flex-1 bg-[#006b43] hover:bg-emerald-800 text-white font-black text-xs uppercase py-3 rounded-xl transition-all">

                        Filter

                    </button>

                    <a
                        href="{{ url()->current() }}"
                        class="px-4 py-3 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl text-xs font-black uppercase">

                        Reset

                    </a>

                </div>

            </div>

        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full text-left border-separate border-spacing-y-3">

                <thead>

                    <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">

                        <th class="px-6 py-3">Pengguna</th>
                        <th class="px-6 py-3">Aktivitas / Tindakan</th>
                        <th class="px-6 py-3 text-center">IP Address</th>
                        <th class="px-6 py-3 text-right">Waktu Kejadian</th>

                    </tr>

                </thead>

                <tbody class="text-xs">

                    @forelse($logs as $log)

                    <tr class="bg-white dark:bg-slate-800/40 hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 rounded-xl shadow-sm border border-slate-100 dark:border-slate-800">

                        {{-- Pengguna --}}
                        <td class="px-6 py-4 rounded-l-xl">

                            <div class="flex items-center gap-3">

                                <div class="w-8 h-8 bg-slate-100 dark:bg-slate-800 rounded-lg flex items-center justify-center text-[#006b43] font-black text-[10px] uppercase">

                                    {{ strtoupper(substr($log->user->nama_lengkap ?? 'SY', 0, 2)) }}

                                </div>

                                <div>

                                    <p class="font-black text-slate-800 dark:text-white">
                                        {{ $log->user->nama_lengkap ?? 'Sistem Otomatis' }}
                                    </p>

                                    @if($log->user)
                                        <p class="text-[10px] text-slate-500">
                                            {{ $log->user->jabatan }}
                                        </p>
                                    @endif

                                    <p class="text-[9px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">

                                        <span class="px-1.5 py-0.5 bg-slate-100 dark:bg-slate-800 rounded-md">

                                            {{ $log->user->role ?? 'SYSTEM' }}

                                        </span>

                                    </p>

                                </div>

                            </div>

                        </td>

                        {{-- Aktivitas --}}
                        <td class="px-6 py-4">

                            <div class="flex flex-col">

                                <span class="font-bold text-emerald-600 dark:text-emerald-400 uppercase">

                                    {{ $log->aktivitas }}

                                </span>

                                @if(!empty($log->deskripsi))
                                    <span class="text-[10px] text-slate-500 italic">
                                        {{ $log->deskripsi }}
                                    </span>
                                @endif

                            </div>

                        </td>

                        {{-- IP --}}
                        <td class="px-6 py-4 text-center">

                            <span class="px-2.5 py-1 bg-slate-100 dark:bg-slate-800 rounded-md font-mono text-[10px]">

                                {{ $log->ip_address ?? '-' }}

                            </span>

                        </td>

                        {{-- Waktu --}}
                        <td class="px-6 py-4 text-right rounded-r-xl">

                            <span class="font-bold text-slate-600 dark:text-slate-300">

                                {{ \Carbon\Carbon::parse($log->waktu_kejadian)->diffForHumans() }}

                            </span>

                            <span class="block text-[10px] text-slate-400 font-mono mt-1">

                                {{ \Carbon\Carbon::parse($log->waktu_kejadian)->format('d M Y H:i:s') }}

                            </span>

                        </td>

                    </tr>

                    @empty

                    <tr>

                        <td colspan="4" class="px-6 py-10 text-center font-black uppercase text-slate-400">

                            Tidak ada data audit log.

                        </td>

                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

      {{-- Pagination (Disempurnakan dengan overflow-x-auto agar tidak keluar dari card) --}}
      <div class="mt-6 pt-2 pb-2 flex flex-col md:flex-row justify-between items-center gap-4">

          <div class="text-[10px] font-black uppercase text-slate-400 shrink-0">
              Menampilkan
              {{ $logs->firstItem() ?? 0 }}
              -
              {{ $logs->lastItem() ?? 0 }}
              dari
              {{ $logs->total() }}
              data
          </div>

          @if($logs->hasPages())
              {{-- Bungkus dengan div overflow-x-auto agar aman dan tidak pernah menembus batas card --}}
              <div class="w-full md:w-auto overflow-x-auto pb-2">
                  <div class="flex items-center justify-end gap-2 min-w-max">

                      {{-- Prev --}}
                      @if($logs->onFirstPage())
                          <span class="px-4 py-2 rounded-xl bg-emerald-100 text-emerald-300 text-xs font-black cursor-not-allowed shrink-0">
                              Prev
                          </span>
                      @else
                          <a href="{{ $logs->previousPageUrl() }}" class="px-4 py-2 rounded-xl bg-emerald-100 hover:bg-emerald-200 text-emerald-700 text-xs font-black transition-all duration-300 shadow-sm shrink-0">
                              Prev
                          </a>
                      @endif

                      {{-- Nomor Halaman --}}
                      @foreach ($logs->getUrlRange(1, $logs->lastPage()) as $page => $url)
                          @if ($page == $logs->currentPage())
                              <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-[#006b43] text-white text-xs font-black shadow-lg shrink-0">
                                  {{ $page }}
                              </span>
                          @else
                              <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-emerald-100 hover:bg-emerald-200 text-emerald-700 text-xs font-black transition-all duration-300 shadow-sm shrink-0">
                                  {{ $page }}
                              </a>
                          @endif
                      @endforeach

                      {{-- Next --}}
                      @if($logs->hasMorePages())
                          <a href="{{ $logs->nextPageUrl() }}" class="px-4 py-2 rounded-xl bg-[#006b43] hover:bg-emerald-800 text-white text-xs font-black transition-all duration-300 shadow-lg shrink-0">
                              Next
                          </a>
                      @else
                          <span class="px-4 py-2 rounded-xl bg-emerald-100 text-emerald-300 text-xs font-black cursor-not-allowed shrink-0">
                              Next
                          </span>
                      @endif

                  </div>
              </div>
          @endif

      </div>

    </div>

</div>

@endsection