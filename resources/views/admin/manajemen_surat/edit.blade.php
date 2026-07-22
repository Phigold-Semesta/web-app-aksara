@extends('layouts.app')

@section('content')
<div class="p-8 transition-colors duration-300 dark:bg-emerald-950/20">
    {{-- Header --}}
    <div class="mb-10">
        {{-- Tombol Kembali Abu-abu Muda --}}
        <a href="{{ route('admin.manajemen_surat.index') }}" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all mb-4 gap-2 shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight">Perbarui Data Surat</h1>
        <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Mengedit arsip digital #{{ $surat->id_surat }}</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl shadow-emerald-900/5 border border-emerald-50 dark:border-slate-800 overflow-hidden">
        <form action="{{ route('admin.manajemen_surat.update', $surat->id_surat) }}" method="POST" enctype="multipart/form-data" class="p-10">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Kolom Kiri --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Nomor Surat</label>
                        <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $surat->nomor_surat) }}" required
                               class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all dark:text-white font-medium">
                        @error('nomor_surat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Asal Instansi</label>
                        <input type="text" name="asal_instansi" value="{{ old('asal_instansi', $surat->asal_instansi) }}" required
                               class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all dark:text-white font-medium">
                        @error('asal_instansi') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Kategori Surat</label>
                        <select name="id_kategori" required
                                class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all dark:text-white font-medium cursor-pointer">
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id_kategori }}" {{ old('id_kategori', $surat->id_kategori) == $kat->id_kategori ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_kategori') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Kolom Kanan --}}
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', $surat->tanggal_surat) }}" required
                               class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all dark:text-white font-medium">
                        @error('tanggal_surat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Perihal / Ringkasan</label>
                        <textarea name="perihal" rows="4" required
                                  class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl focus:ring-4 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all dark:text-white font-medium">{{ old('perihal', $surat->perihal) }}</textarea>
                        @error('perihal') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Edit File Section dengan Konsep Preview Dokumen Digital Viewer --}}
            <div class="mt-10 p-8 border-2 border-dashed border-emerald-200 dark:border-slate-700 rounded-[2rem] bg-emerald-50/30 dark:bg-slate-800/20">
                
                {{-- Area Upload / Input File Baru --}}
                <div class="mb-6">
                    <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Ganti Dokumen</label>
                    <input type="file" name="file_surat" id="file_dokumen" accept=".pdf,.jpg,.jpeg,.png"
                           class="block w-full text-sm text-emerald-500 file:mr-4 file:py-3 file:px-8 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-emerald-600 file:text-white hover:file:bg-emerald-700 transition-all cursor-pointer">
                    <p class="mt-2 text-[10px] text-emerald-400 italic">*Biarkan kosong jika tidak ingin mengganti file.</p>
                    @error('file_surat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- State Preview Digital Viewer (Otomatis Menampilkan Dokumen Aktif / File Baru) --}}
                <div id="filePreviewContainer" class="flex flex-col w-full bg-white dark:bg-slate-900 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                    
                    {{-- Header Kotak Viewer Digital --}}
                    <div class="bg-slate-100 dark:bg-slate-950 px-6 py-3 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-file-pdf text-emerald-600"></i>
                            <span class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-emerald-300">Preview Dokumen Digital</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ $surat->file_surat ? asset('storage/dokumen_surat/' . $surat->file_surat) : '#' }}" id="openFullScreen" target="_blank" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 flex items-center gap-1 transition-all">
                                Buka Layar Penuh <i class="fas fa-external-link-alt text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Toolbar Simulasi Viewer --}}
                    <div class="bg-slate-50 dark:bg-slate-800/80 px-4 py-2 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between text-xs text-slate-500 font-semibold">
                        <div class="flex items-center gap-4">
                            <span id="fileNameDisplay" class="truncate max-w-xs text-slate-700 dark:text-slate-200 font-mono">{{ $surat->file_surat ? basename($surat->file_surat) : 'Tidak ada file' }}</span>
                        </div>
                        <div>
                            <span id="fileSizeDisplay" class="bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 px-2.5 py-0.5 rounded-md text-[10px] font-bold">File Tersimpan</span>
                        </div>
                    </div>

                    {{-- Area Render Konten Viewer (Embed / Iframe / Gambar) --}}
                    <div class="w-full h-96 bg-slate-100 dark:bg-slate-950 flex items-center justify-center relative overflow-hidden p-2" id="previewWrapper">
                        @if($surat->file_surat)
                            @php
                                $extension = pathinfo($surat->file_surat, PATHINFO_EXTENSION);
                                $fileUrl = asset('storage/dokumen_surat/' . $surat->file_surat);
                            @endphp
                            @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp']))
                                <img src="{{ $fileUrl }}" class="w-full h-full object-contain rounded-xl">
                            @else
                                <embed src="{{ $fileUrl }}#toolbar=0" type="application/pdf" class="w-full h-full rounded-xl">
                            @endif
                        @else
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <i class="fas fa-file-alt text-4xl mb-2"></i>
                                <p class="text-xs font-bold">Belum ada file dokumen yang diunggah.</p>
                            </div>
                        @endif
                    </div>

                </div>

            </div>

            {{-- Tombol Submit Form Murni --}}
            <div class="mt-10 flex justify-end">
                <button type="submit" class="px-12 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black shadow-xl shadow-emerald-200 dark:shadow-none transition-all transform hover:-translate-y-1 cursor-pointer">
                    SIMPAN PERUBAHAN
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file_dokumen');
        const previewWrapper = document.getElementById('previewWrapper');
        const fileNameDisplay = document.getElementById('fileNameDisplay');
        const fileSizeDisplay = document.getElementById('fileSizeDisplay');
        const openFullScreen = document.getElementById('openFullScreen');

        // Fungsi ketika file baru dipilih untuk mengganti preview dokumen secara live
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                fileNameDisplay.textContent = file.name;
                const fileSizeKB = (file.size / 1024).toFixed(2);
                fileSizeDisplay.textContent = `${fileSizeKB} KB (File Baru)`;

                previewWrapper.innerHTML = '';

                const fileURL = URL.createObjectURL(file);
                openFullScreen.href = fileURL;

                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = fileURL;
                    img.className = 'w-full h-full object-contain rounded-xl';
                    previewWrapper.appendChild(img);
                } else if (file.type === 'application/pdf') {
                    const embed = document.createElement('embed');
                    embed.src = fileURL + '#toolbar=0';
                    embed.type = 'application/pdf';
                    embed.className = 'w-full h-full rounded-xl';
                    previewWrapper.appendChild(embed);
                } else {
                    const fallbackDiv = document.createElement('div');
                    fallbackDiv.className = 'flex flex-col items-center justify-center text-slate-500';
                    fallbackDiv.innerHTML = '<i class="fas fa-file-alt text-4xl mb-2"></i><p class="text-xs font-bold">Pratinjau tidak tersedia untuk format ini.</p>';
                    previewWrapper.appendChild(fallbackDiv);
                }
            });
        }
    });
</script>
@endsection