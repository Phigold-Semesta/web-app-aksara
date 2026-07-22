@extends('layouts.app')

@section('title', 'Manajemen Surat & Disposisi - Aksara')

@section('content')
<div class="p-2 md:p-4 space-y-10 animate__animated animate__fadeIn">
    
    {{-- Header Command Center Section --}}
    <div class="relative overflow-hidden bg-[#006b43] rounded-[2.5rem] p-8 md:p-12 text-white shadow-2xl border border-emerald-400/20">
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
            <div class="space-y-4 text-center md:text-left">
                <p class="text-emerald-200 text-xs md:text-sm font-bold uppercase tracking-[0.3em]">Executive Control</p>
                <h1 class="text-4xl md:text-6xl font-black tracking-tighter leading-none uppercase italic">
                    Manajemen Surat<br>& Disposisi
                </h1>
                <p class="text-emerald-100/80 text-xs font-medium">Pusat tinjauan dokumen masuk, penandatanganan, dan riwayat tindakan pimpinan.</p>
            </div>
            
            {{-- Card Kaca Center Simetris --}}
            <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-[2rem] w-full md:w-80 shadow-2xl flex flex-col items-center justify-center text-center shrink-0">
                <p class="text-[10px] font-black uppercase text-emerald-300 tracking-[0.2em]">Total Perlu Ditinjau</p>
                <h2 class="text-5xl font-black text-white my-1 tracking-tighter">{{ $suratMasuk->total() ?? $suratMasuk->count() }}</h2>
                <span class="inline-flex items-center justify-center bg-emerald-400/20 text-emerald-200 px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest italic border border-emerald-300/30 mt-1">
                    <i class="fas fa-exclamation-circle mr-1.5"></i> Prioritas Arahan
                </span>
            </div>
        </div>
    </div>

    {{-- SECTION 1: SURAT MASUK (BUTUH INSTRUKSI DISPOSISI) --}}
    <section class="space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-3.5 h-3.5 bg-red-500 rounded-full animate-pulse shadow-lg shadow-red-500/50"></span>
                <div>
                    <h3 class="text-lg font-black uppercase tracking-tight text-slate-800 dark:text-white italic">
                        Butuh Instruksi Disposisi
                    </h3>
                    <p class="text-xs text-slate-400 dark:text-emerald-400/70 font-medium">Dokumen masuk yang memerlukan penandatanganan & arahan pimpinan</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-2xl space-y-6">
            
            {{-- Form Control Bar Surat Masuk (GET Request) --}}
            <form action="{{ route('pimpinan.manajemen_surat.index') }}" method="GET" id="formSuratMasuk" class="bg-emerald-50/50 dark:bg-emerald-950/20 p-4 rounded-2xl border border-emerald-100/50 dark:border-emerald-800/40 flex flex-wrap gap-4 items-center justify-between relative z-10">
                
                {{-- Pertahankan state tab riwayat jika sedang aktif --}}
                @if(request('search_riwayat') || request('per_page_riwayat') || request('page_riwayat'))
                    <input type="hidden" name="search_riwayat" value="{{ request('search_riwayat') }}">
                    <input type="hidden" name="per_page_riwayat" value="{{ request('per_page_riwayat') }}">
                @endif

                {{-- Filter Jumlah Baris Surat Masuk --}}
                <div class="flex items-center gap-3">
                    <span class="text-[11px] font-bold text-emerald-900 dark:text-emerald-200 uppercase">Tampilkan:</span>
                    <div class="relative">
                        <select name="per_page_masuk" onchange="document.getElementById('formSuratMasuk').submit()" class="appearance-none bg-white dark:bg-slate-800 border border-emerald-200 dark:border-slate-700 rounded-xl px-5 py-2.5 pr-8 text-xs font-bold text-emerald-900 dark:text-emerald-100 focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer">
                            <option value="5" {{ request('per_page_masuk', 5) == 5 ? 'selected' : '' }}>5 Baris</option>
                            <option value="10" {{ request('per_page_masuk') == 10 ? 'selected' : '' }}>10 Baris</option>
                            <option value="25" {{ request('per_page_masuk') == 25 ? 'selected' : '' }}>25 Baris</option>
                            <option value="-1" {{ request('per_page_masuk') == -1 ? 'selected' : '' }}>Semua Data</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-emerald-500 pointer-events-none"></i>
                    </div>
                </div>

                {{-- Input Searching Surat Masuk --}}
                <div class="flex items-center gap-3 w-full sm:w-80">
                    <div class="relative w-full">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-emerald-400 text-xs"></i>
                        <input type="text" name="search_masuk" value="{{ request('search_masuk') }}" placeholder="Cari nomor surat atau perihal..." class="w-full bg-white dark:bg-slate-800 border border-emerald-200 dark:border-slate-700 rounded-xl pl-10 pr-4 py-2.5 text-xs font-medium text-emerald-900 dark:text-emerald-100 placeholder-emerald-300 outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    @if(request('search_masuk'))
                        <button type="submit" onclick="document.querySelector('[name=search_masuk]').value=''" class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-3 py-2.5 rounded-xl text-xs font-bold hover:bg-red-100 transition-all border border-red-100 shrink-0">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    @endif
                </div>
            </form>

            {{-- Tabel Data Surat Masuk --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-4">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                            <th class="px-6 py-4">No. Surat</th>
                            <th class="px-6 py-4">Perihal</th>
                            <th class="px-6 py-4">Tanggal Masuk</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        @forelse($suratMasuk as $surat)
                        <tr class="bg-white dark:bg-slate-800/50 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all rounded-[1.5rem] shadow-sm border border-slate-100 dark:border-slate-800">
                            <td class="p-6 rounded-l-[1.5rem] font-black text-emerald-600 dark:text-emerald-400 uppercase">
                                {{ $surat->nomor_surat }}
                            </td>
                            <td class="p-6 font-bold text-slate-800 dark:text-slate-200">
                                {{ $surat->perihal }}
                            </td>
                            <td class="p-6 text-slate-500 font-semibold">
                                {{ $surat->created_at ? $surat->created_at->format('d M Y') : '-' }}
                            </td>
                            <td class="p-6 text-center rounded-r-[1.5rem]">
                                <a href="{{ route('pimpinan.manajemen_surat.show', $surat->id_surat) }}" 
                                   class="inline-flex items-center gap-2 bg-[#006b43] hover:bg-emerald-800 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all shadow-md shadow-emerald-900/20 hover:scale-105">
                                   <i class="fas fa-file-signature"></i> TINJAU
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center text-slate-400 font-bold italic text-xs">
                                Tidak ada surat masuk yang memerlukan instruksi saat ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- CUSTOM PAGINATION CONTAINER (SURAT MASUK) --}}
            <div class="bg-emerald-50/30 dark:bg-emerald-950/10 rounded-[2rem] p-4 sm:p-5 border border-emerald-100/50 dark:border-emerald-800/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-[11px] font-black uppercase tracking-wider text-emerald-700 dark:text-emerald-400 pl-2">
                    MENAMPILKAN {{ $suratMasuk->firstItem() ?? 0 }} – {{ $suratMasuk->lastItem() ?? 0 }} DARI {{ $suratMasuk->total() }} DATA
                </div>

                @if($suratMasuk->hasPages())
                <div class="flex items-center gap-2">
                    {{-- Prev --}}
                    @if ($suratMasuk->onFirstPage())
                        <span class="px-4 py-2 rounded-full text-xs font-bold bg-emerald-100/30 text-emerald-300 cursor-not-allowed">Prev</span>
                    @else
                        <a href="{{ $suratMasuk->previousPageUrl() }}" class="px-4 py-2 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-all">Prev</a>
                    @endif

                    {{-- Numbered Pages --}}
                    @foreach ($suratMasuk->getUrlRange(1, $suratMasuk->lastPage()) as $page => $url)
                        @if ($page == $suratMasuk->currentPage())
                            <span class="w-9 h-9 flex items-center justify-center rounded-full text-xs font-black bg-[#006b43] text-white shadow-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition-all">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($suratMasuk->hasMorePages())
                        <a href="{{ $suratMasuk->nextPageUrl() }}" class="px-5 py-2 rounded-full text-xs font-black bg-[#006b43] text-white hover:bg-emerald-800 transition-all shadow-md">Next</a>
                    @else
                        <span class="px-5 py-2 rounded-full text-xs font-black bg-emerald-100/30 text-emerald-300 cursor-not-allowed">Next</span>
                    @endif
                </div>
                @endif
            </div>

        </div>
    </section>

    {{-- SECTION 2: RIWAYAT DISPOSISI --}}
    <section class="space-y-6 pt-4">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black uppercase tracking-tight text-slate-800 dark:text-white italic flex items-center gap-2">
                    <i class="fas fa-history text-[#006b43]"></i> Riwayat Tindakan & Disposisi
                </h3>
                <p class="text-xs text-slate-400 dark:text-emerald-400/70 font-medium">Daftar rekaman disposisi dan instruksi yang telah diterbitkan pimpinan</p>
            </div>
        </div>
        
        <div class="bg-white dark:bg-slate-900 p-8 rounded-[2.5rem] border border-slate-100 dark:border-slate-800 shadow-2xl space-y-6">
            
            {{-- Form Control Bar Riwayat (GET Request) --}}
            <form action="{{ route('pimpinan.manajemen_surat.index') }}" method="GET" id="formRiwayat" class="bg-emerald-50/50 dark:bg-emerald-950/20 p-4 rounded-2xl border border-emerald-100/50 dark:border-emerald-800/40 flex flex-wrap gap-4 items-center justify-between relative z-10">
                
                {{-- Pertahankan state surat masuk jika sedang aktif --}}
                @if(request('search_masuk') || request('per_page_masuk') || request('page_masuk'))
                    <input type="hidden" name="search_masuk" value="{{ request('search_masuk') }}">
                    <input type="hidden" name="per_page_masuk" value="{{ request('per_page_masuk') }}">
                @endif

                {{-- Filter Jumlah Baris Riwayat --}}
                <div class="flex items-center gap-3">
                    <span class="text-[11px] font-bold text-emerald-900 dark:text-emerald-200 uppercase">Tampilkan:</span>
                    <div class="relative">
                        <select name="per_page_riwayat" onchange="document.getElementById('formRiwayat').submit()" class="appearance-none bg-white dark:bg-slate-800 border border-emerald-200 dark:border-slate-700 rounded-xl px-5 py-2.5 pr-8 text-xs font-bold text-emerald-900 dark:text-emerald-100 focus:ring-2 focus:ring-emerald-500 outline-none cursor-pointer">
                            <option value="5" {{ request('per_page_riwayat', 5) == 5 ? 'selected' : '' }}>5 Baris</option>
                            <option value="10" {{ request('per_page_riwayat') == 10 ? 'selected' : '' }}>10 Baris</option>
                            <option value="25" {{ request('per_page_riwayat') == 25 ? 'selected' : '' }}>25 Baris</option>
                            <option value="-1" {{ request('per_page_riwayat') == -1 ? 'selected' : '' }}>Semua Data</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-[9px] text-emerald-500 pointer-events-none"></i>
                    </div>
                </div>

                {{-- Input Searching Riwayat --}}
                <div class="flex items-center gap-3 w-full sm:w-80">
                    <div class="relative w-full">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-emerald-400 text-xs"></i>
                        <input type="text" name="search_riwayat" value="{{ request('search_riwayat') }}" placeholder="Cari nomor surat atau instruksi..." class="w-full bg-white dark:bg-slate-800 border border-emerald-200 dark:border-slate-700 rounded-xl pl-10 pr-4 py-2.5 text-xs font-medium text-emerald-900 dark:text-emerald-100 placeholder-emerald-300 outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    @if(request('search_riwayat'))
                        <button type="submit" onclick="document.querySelector('[name=search_riwayat]').value=''" class="bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-3 py-2.5 rounded-xl text-xs font-bold hover:bg-red-100 transition-all border border-red-100 shrink-0">
                            <i class="fas fa-times-circle"></i>
                        </button>
                    @endif
                </div>
            </form>

            {{-- Tabel Data Riwayat --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-4">
                    <thead>
                        <tr class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                            <th class="px-6 py-4">Surat</th>
                            <th class="px-6 py-4">Instruksi Pimpinan</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        @forelse($riwayat as $r)
                        <tr class="bg-white dark:bg-slate-800/50 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-all rounded-[1.5rem] shadow-sm border border-slate-100 dark:border-slate-800">
                            <td class="p-6 rounded-l-[1.5rem] font-black text-slate-800 dark:text-slate-200 uppercase">
                                {{ $r->surat->nomor_surat ?? 'N/A' }}
                            </td>
                            <td class="p-6 font-bold text-emerald-700 dark:text-emerald-300">
                                <span class="bg-emerald-50 dark:bg-emerald-950/50 px-3 py-1 rounded-xl border border-emerald-200 dark:border-emerald-800">
                                    {{ $r->instruksi_disposisi->nama_instruksi ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="p-6 text-slate-500 font-semibold">
                                {{ $r->created_at ? $r->created_at->format('d M Y') : '-' }}
                            </td>
                            <td class="p-6 rounded-r-[1.5rem] text-center">
                                <div class="inline-flex items-center justify-center gap-2">
                                    <a href="{{ route('pimpinan.manajemen_surat.riwayat', $r->surat->id_surat) }}" 
                                       class="p-2.5 bg-emerald-50 dark:bg-slate-800 text-emerald-600 dark:text-emerald-400 rounded-xl hover:bg-[#006b43] hover:text-white transition-all shadow-sm" 
                                       title="Lihat Detail Riwayat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    {{-- FORM HAPUS DENGAN ID UNIK YANG BENAR --}}
                                    <form action="{{ route('pimpinan.manajemen_surat.destroy_riwayat', $r->id_disposisi) }}" method="POST" id="delete-form-{{ $r->id_disposisi }}" class="inline-block m-0 p-0">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="button" 
                                                onclick="konfirmasiHapus('{{ $r->id_disposisi }}')" 
                                                class="p-2.5 bg-red-50 dark:bg-slate-800 text-red-600 dark:text-red-400 rounded-xl hover:bg-red-600 hover:text-white transition-all shadow-sm cursor-pointer" 
                                                title="Hapus Riwayat">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="p-12 text-center text-slate-400 font-bold italic text-xs">
                                Belum ada riwayat tindakan disposisi yang tercatat.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- CUSTOM PAGINATION CONTAINER (RIWAYAT DISPOSISI) --}}
            <div class="bg-emerald-50/30 dark:bg-emerald-950/10 rounded-[2rem] p-4 sm:p-5 border border-emerald-100/50 dark:border-emerald-800/30 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="text-[11px] font-black uppercase tracking-wider text-emerald-700 dark:text-emerald-400 pl-2">
                    MENAMPILKAN {{ $riwayat->firstItem() ?? 0 }} – {{ $riwayat->lastItem() ?? 0 }} DARI {{ $riwayat->total() }} DATA
                </div>

                @if($riwayat->hasPages())
                <div class="flex items-center gap-2">
                    {{-- Prev --}}
                    @if ($riwayat->onFirstPage())
                        <span class="px-4 py-2 rounded-full text-xs font-bold bg-emerald-100/30 text-emerald-300 cursor-not-allowed">Prev</span>
                    @else
                        <a href="{{ $riwayat->previousPageUrl() }}" class="px-4 py-2 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 hover:bg-emerald-200 transition-all">Prev</a>
                    @endif

                    {{-- Numbered Pages --}}
                    @foreach ($riwayat->getUrlRange(1, $riwayat->lastPage()) as $page => $url)
                        @if ($page == $riwayat->currentPage())
                            <span class="w-9 h-9 flex items-center justify-center rounded-full text-xs font-black bg-[#006b43] text-white shadow-md">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition-all">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($riwayat->hasMorePages())
                        <a href="{{ $riwayat->nextPageUrl() }}" class="px-5 py-2 rounded-full text-xs font-black bg-[#006b43] text-white hover:bg-emerald-800 transition-all shadow-md">Next</a>
                    @else
                        <span class="px-5 py-2 rounded-full text-xs font-black bg-emerald-100/30 text-emerald-300 cursor-not-allowed">Next</span>
                    @endif
                </div>
                @endif
            </div>

        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Function Konfirmasi Hapus SweetAlert2
    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus Riwayat?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-[2rem]'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('delete-form-' + id);
                if (form) {
                    form.submit();
                } else {
                    console.error('Form delete-form-' + id + ' tidak ditemukan!');
                }
            }
        });
    }
</script>
@endpush