@extends('layouts.app')

@section('content')
{{-- Library jsPDF tidak lagi diperlukan karena AI Scanner dihapus --}}

<div class="p-8 min-h-screen transition-colors duration-300 dark:bg-emerald-950/20">
    {{-- Header --}}
    <div class="mb-10">
        {{-- PERBAIKAN: Tombol Kembali Abu-abu Muda --}}
        <a href="{{ route('petugas.manajemen_surat.index') }}" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all mb-4 gap-2 shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-white tracking-tight">Manajemen Surat</h1>
        <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Upload dokumen surat baru untuk pengarsipan LPSE Karawang</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border border-emerald-50 dark:border-slate-800 overflow-hidden">
        <form action="{{ route('petugas.manajemen_surat.store') }}" method="POST" enctype="multipart/form-data" id="suratForm" class="p-10">
            @csrf
            
            {{-- Bagian Input Data --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Nomor Surat</label>
                        <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" required class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl outline-none focus:border-emerald-500 dark:text-white transition-all">
                        @error('nomor_surat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Asal Instansi</label>
                        <input type="text" name="asal_instansi" value="{{ old('asal_instansi') }}" required class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl outline-none focus:border-emerald-500 dark:text-white transition-all">
                    </div>
                </div>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}" required class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl outline-none focus:border-emerald-500 dark:text-white transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Kategori</label>
                        <select name="id_kategori" required class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl outline-none focus:border-emerald-500 dark:text-white transition-all">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id_kategori }}" {{ old('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Kolom Perihal --}}
            <div class="mb-10">
                <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Perihal</label>
                <textarea name="perihal" rows="3" required class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl outline-none focus:border-emerald-500 dark:text-white transition-all" placeholder="Tuliskan perihal atau ringkasan surat...">{{ old('perihal') }}</textarea>
            </div>

            {{-- Area Upload Dokumen (Fitur utama dipertahankan) --}}
            <div class="p-12 border-2 border-dashed border-emerald-200 dark:border-slate-700 rounded-[2rem] bg-emerald-50/20 flex flex-col items-center justify-center">
                <div class="mb-4 text-emerald-600">
                    <i class="fas fa-file-upload text-5xl"></i>
                </div>
                <h3 class="text-lg font-bold text-emerald-900 dark:text-emerald-100">Upload Dokumen Surat</h3>
                <p class="text-sm text-emerald-600 mb-6">Pilih file dokumen fisik (PDF, JPG, PNG)</p>
                <input type="file" name="file_dokumen" id="file_dokumen" accept=".pdf,.jpg,.jpeg,.png" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 cursor-pointer">
            </div>

            {{-- Input Hidden (Disederhanakan) --}}
            <input type="hidden" name="input_method" value="manual">

            <div class="mt-10 flex justify-end">
                <button type="submit" id="btnSubmit" class="px-12 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black shadow-xl transition-all transform hover:scale-105">
                    SIMPAN DOKUMEN KE ARSIP DIGITAL
                </button>
            </div>
            
            {{-- Spacer untuk mempertahankan struktur file asli --}}
            <div class="hidden">
                {{-- Area ini dibiarkan kosong agar struktur desain tetap sama dengan aslinya --}}
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('suratForm').onsubmit = function() {
        const fileInput = document.getElementById('file_dokumen');
        
        // Validasi file
        if (fileInput.files.length === 0) {
            alert("Bos, file dokumennya belum dipilih!");
            return false;
        }
        
        // Feedback tombol
        document.getElementById('btnSubmit').innerText = "PROSES MENYIMPAN...";
        document.getElementById('btnSubmit').disabled = true;
        return true;
    };
</script>

{{-- Placeholder untuk menjaga struktur baris file tetap konsisten --}}
<style>
    /* Styling tambahan untuk menjaga kebersihan desain */
    .file-input-wrapper { @apply mt-4; }
</style>
<div class="hidden">
    </div>
@endsection