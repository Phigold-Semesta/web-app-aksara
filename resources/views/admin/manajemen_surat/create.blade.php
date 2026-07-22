@extends('layouts.app')

@section('content')
<div class="p-8 min-h-screen transition-colors duration-300 dark:bg-emerald-950/20">
    {{-- Header --}}
    <div class="mb-10">
        {{-- Tombol Kembali Abu-abu Muda --}}
        <a href="{{ route('admin.manajemen_surat.index') }}" class="inline-flex items-center bg-gray-200 hover:bg-gray-300 text-gray-700 px-5 py-2.5 rounded-xl font-bold text-sm transition-all mb-4 gap-2 shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-white tracking-tight uppercase italic">Tambah Data Surat</h1>
        <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Upload dokumen surat baru untuk pengarsipan LPSE Karawang</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border border-emerald-50 dark:border-slate-800 overflow-hidden">
        <form action="{{ route('admin.manajemen_surat.store') }}" method="POST" enctype="multipart/form-data" id="suratForm" class="p-10">
            @csrf
            
            {{-- Bagian Input Data --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-6">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Nomor Surat</label>
                        <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" required placeholder="Contoh: 005/LPSE/2026" class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl outline-none focus:border-emerald-500 dark:text-white transition-all font-bold text-xs">
                        @error('nomor_surat') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Asal Instansi</label>
                        <input type="text" name="asal_instansi" value="{{ old('asal_instansi') }}" required placeholder="Contoh: Dinas Komunikasi dan Informatika" class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl outline-none focus:border-emerald-500 dark:text-white transition-all font-bold text-xs">
                        @error('asal_instansi') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}" required class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl outline-none focus:border-emerald-500 dark:text-white transition-all font-bold text-xs">
                        @error('tanggal_surat') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Kategori</label>
                        <select name="id_kategori" required class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl outline-none focus:border-emerald-500 dark:text-white transition-all font-bold text-xs cursor-pointer">
                            <option value="">Pilih Kategori Surat</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id_kategori }}" {{ old('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                        @error('id_kategori') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            {{-- Kolom Perihal --}}
            <div class="mb-10">
                <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Perihal</label>
                <textarea name="perihal" rows="3" required class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl outline-none focus:border-emerald-500 dark:text-white transition-all font-bold text-xs" placeholder="Tuliskan perihal atau ringkasan surat...">{{ old('perihal') }}</textarea>
                @error('perihal') <p class="text-red-500 text-xs mt-1 font-bold">{{ $message }}</p> @enderror
            </div>

            {{-- Area Upload Dokumen dengan Live Preview Standar Viewer Digital --}}
            <div class="p-8 border-2 border-dashed border-emerald-200 dark:border-slate-700 rounded-[2rem] bg-emerald-50/20 flex flex-col items-center justify-center relative transition-all" id="dropZone">
                
                {{-- State Default (Sebelum File Dipilih) --}}
                <div id="uploadPrompt" class="flex flex-col items-center justify-center py-6">
                    <div class="mb-4 text-emerald-600">
                        <i class="fas fa-file-upload text-5xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-emerald-900 dark:text-emerald-100">Upload Dokumen Surat</h3>
                    <p class="text-sm text-emerald-600 mb-6">Pilih file dokumen fisik (PDF, JPG, PNG)</p>
                    <input type="file" name="file_surat" id="file_dokumen" accept=".pdf,.jpg,.jpeg,.png" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-[#006b43] file:text-white hover:file:bg-emerald-800 cursor-pointer">
                    @error('file_surat') <p class="text-red-500 text-xs mt-2 font-bold">{{ $message }}</p> @enderror
                </div>

                {{-- State Preview Digital Viewer (Setelah File Dipilih) --}}
                <div id="filePreviewContainer" class="hidden flex flex-col w-full bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                    
                    {{-- Header Kotak Viewer Digital --}}
                    <div class="bg-slate-100 dark:bg-slate-900 px-6 py-3 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-file-pdf text-emerald-600"></i>
                            <span class="text-xs font-black uppercase tracking-wider text-slate-700 dark:text-emerald-300">Preview Dokumen Digital</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="#" id="openFullScreen" target="_blank" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 flex items-center gap-1 transition-all">
                                Buka Layar Penuh <i class="fas fa-external-link-alt text-[10px]"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Toolbar Simulasi Viewer --}}
                    <div class="bg-slate-50 dark:bg-slate-800/80 px-4 py-2 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between text-xs text-slate-500 font-semibold">
                        <div class="flex items-center gap-4">
                            <span id="fileNameDisplay" class="truncate max-w-xs text-slate-700 dark:text-slate-200"></span>
                        </div>
                        <div>
                            <span id="fileSizeDisplay" class="bg-emerald-100 dark:bg-emerald-900/50 text-emerald-700 dark:text-emerald-300 px-2.5 py-0.5 rounded-md text-[10px] font-bold"></span>
                        </div>
                    </div>

                    {{-- Area Render Konten Viewer (Embed / Iframe / Gambar) --}}
                    <div class="w-full h-96 bg-slate-100 dark:bg-slate-950 flex items-center justify-center relative overflow-hidden p-2" id="previewWrapper">
                        <!-- Konten dinamis dimasukkan via JavaScript -->
                    </div>

                    {{-- Footer Viewer dengan Tombol Hapus/Ganti --}}
                    <div class="bg-slate-50 dark:bg-slate-900 px-6 py-3 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                        <button type="button" id="removeFileBtn" class="px-4 py-2 bg-red-100 hover:bg-red-200 text-red-600 rounded-xl text-xs font-bold transition-all flex items-center gap-2 cursor-pointer">
                            <i class="fas fa-trash-alt"></i> Hapus / Ganti File Lain
                        </button>
                    </div>

                </div>

            </div>

            <div class="mt-10 flex justify-end">
                <button type="submit" id="btnSubmit" class="px-12 py-4 bg-[#006b43] hover:bg-emerald-800 text-white rounded-2xl font-black shadow-xl transition-all transform hover:scale-105 uppercase tracking-widest text-xs flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Dokumen
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('file_dokumen');
        const uploadPrompt = document.getElementById('uploadPrompt');
        const filePreviewContainer = document.getElementById('filePreviewContainer');
        const previewWrapper = document.getElementById('previewWrapper');
        const fileNameDisplay = document.getElementById('fileNameDisplay');
        const fileSizeDisplay = document.getElementById('fileSizeDisplay');
        const removeFileBtn = document.getElementById('removeFileBtn');
        const openFullScreen = document.getElementById('openFullScreen');

        // Fungsi ketika file dipilih
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Tampilkan informasi file pada header viewer
            fileNameDisplay.textContent = file.name;
            const fileSizeKB = (file.size / 1024).toFixed(2);
            fileSizeDisplay.textContent = `${fileSizeKB} KB`;

            // Kosongkan wrapper preview sebelumnya
            previewWrapper.innerHTML = '';

            const fileURL = URL.createObjectURL(file);
            openFullScreen.href = fileURL;

            // Cek apakah file berupa gambar (JPG, PNG) atau PDF
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

            // Transisi Tampilan: Sembunyikan prompt upload, tampilkan digital viewer container
            uploadPrompt.classList.add('hidden');
            filePreviewContainer.classList.remove('hidden');
        });

        // Tombol untuk mereset pilihan file
        removeFileBtn.addEventListener('click', function() {
            fileInput.value = ''; // Reset input file
            filePreviewContainer.classList.add('hidden');
            uploadPrompt.classList.remove('hidden');
            previewWrapper.innerHTML = '';
        });

        // Validasi saat form disubmit
        document.getElementById('suratForm').onsubmit = function() {
            if (fileInput.files.length === 0) {
                alert("Maaf, file dokumennya belum dipilih. Silakan pilih file dokumen terlebih dahulu.");
                return false;
            }
            
            // Feedback tombol
            const btn = document.getElementById('btnSubmit');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> MENYIMPAN...';
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            return true;
        };
    });
</script>

<style>
    .file-input-wrapper { @apply mt-4; }
</style>
@endsection