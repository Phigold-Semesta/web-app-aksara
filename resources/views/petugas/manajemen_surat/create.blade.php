@extends('layouts.app')

@section('content')
{{-- Library jsPDF & OpenCV --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script async src="https://docs.opencv.org/4.5.4/opencv.js" onload="onOpenCvReady()"></script>

<div class="p-8 transition-colors duration-300">
    {{-- Header --}}
    <div class="mb-10">
        <a href="{{ route('petugas.manajemen_surat.index') }}" class="group flex items-center text-emerald-600 dark:text-emerald-400 font-semibold mb-4 transition-all">
            <i class="fas fa-arrow-left mr-2 transform group-hover:-translate-x-1 transition-transform"></i>
            Kembali ke Daftar Surat
        </a>
        <h1 class="text-3xl font-extrabold text-emerald-950 dark:text-emerald-50 tracking-tight">AI Smart Scanner</h1>
        <p class="text-emerald-600 dark:text-emerald-400 font-medium mt-1">Sistem akan mendeteksi dan memotong dokumen secara otomatis</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-white dark:bg-slate-900 rounded-[2.5rem] shadow-2xl border border-emerald-50 dark:border-slate-800 overflow-hidden">
        <form action="{{ route('petugas.manajemen_surat.store') }}" method="POST" enctype="multipart/form-data" id="suratForm" class="p-10">
            @csrf
            
            {{-- Bagian Input Data --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Nomor Surat</label>
                        <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" required class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl outline-none focus:border-emerald-500 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Asal Instansi</label>
                        <input type="text" name="asal_instansi" value="{{ old('asal_instansi') }}" required class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl outline-none focus:border-emerald-500 dark:text-white">
                    </div>
                </div>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Tanggal Surat</label>
                        <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat') }}" required class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl outline-none focus:border-emerald-500 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-black text-emerald-900 dark:text-emerald-100 uppercase tracking-widest mb-3">Kategori</label>
                        <select name="id_kategori" required class="w-full px-6 py-4 bg-emerald-50/50 dark:bg-slate-800 border border-emerald-100 dark:border-slate-700 rounded-2xl outline-none focus:border-emerald-500 dark:text-white">
                            <option value="">Pilih Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id_kategori }}" {{ old('id_kategori') == $kat->id_kategori ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Smart Scanner Section --}}
            <div class="p-8 border-2 border-dashed border-blue-200 dark:border-slate-700 rounded-[2rem] bg-blue-50/30 flex flex-col items-center">
                <div id="scannerStatus" class="mb-4 text-xs font-bold text-blue-600 uppercase tracking-tighter italic">Memuat AI Engine...</div>
                
                <div id="cameraArea" class="hidden w-full max-w-md mb-4 overflow-hidden rounded-xl bg-black relative shadow-2xl">
                    <video id="video" class="w-full h-full object-cover" autoplay playsinline></video>
                    <div class="absolute inset-0 border-[30px] border-black/40 pointer-events-none">
                        <div class="w-full h-full border-2 border-dashed border-emerald-400 animate-pulse"></div>
                    </div>
                </div>

                <div id="scanResult" class="hidden w-full max-w-md mb-4 text-center">
                    <h4 class="text-sm font-bold text-emerald-700 mb-2 uppercase">Hasil Crop AI:</h4>
                    <canvas id="canvasOutput" class="w-full rounded-lg shadow-2xl border-4 border-white"></canvas>
                </div>

                <div class="flex flex-wrap justify-center gap-4">
                    <button type="button" id="btnStart" onclick="startCamera()" class="px-8 py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg transition-all opacity-50 cursor-not-allowed" disabled>
                        TUNGGU ENGINE...
                    </button>
                    <button type="button" id="btnCapture" onclick="processAndCrop()" class="hidden px-8 py-3 bg-emerald-600 text-white rounded-xl font-bold shadow-lg flex items-center gap-2 transform hover:scale-105 active:scale-95 transition-all">
                        <i class="fas fa-magic"></i> AUTO CROP & SCAN
                    </button>
                    <button type="button" id="btnReset" onclick="resetScan()" class="hidden px-8 py-3 bg-red-500 text-white rounded-xl font-bold shadow-md">
                        ULANGI SCAN
                    </button>
                </div>
            </div>

            {{-- Input Hidden untuk menyimpan Base64 PDF --}}
            <input type="hidden" name="pdf_base64" id="pdf_base64">

            <div class="mt-10 flex justify-end">
                <button type="submit" id="btnSubmit" class="px-12 py-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black shadow-xl transition-all transform hover:scale-105">
                    SIMPAN DOKUMEN KE ARSIP DIGITAL
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let video = document.getElementById('video');
    let canvasOutput = document.getElementById('canvasOutput');
    let scannerStatus = document.getElementById('scannerStatus');
    let btnStart = document.getElementById('btnStart');
    let stream = null;

    function onOpenCvReady() {
        scannerStatus.innerHTML = '<span class="text-emerald-600"><i class="fas fa-check-circle"></i> AI Engine Ready!</span>';
        btnStart.disabled = false;
        btnStart.classList.remove('opacity-50', 'cursor-not-allowed');
        btnStart.innerText = "AKTIFKAN KAMERA SCANNER";
    }

    async function startCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({ 
                video: { facingMode: "environment", width: { ideal: 1280 }, height: { ideal: 720 } }, 
                audio: false 
            });
            video.srcObject = stream;
            document.getElementById('cameraArea').classList.remove('hidden');
            document.getElementById('btnStart').classList.add('hidden');
            document.getElementById('btnCapture').classList.remove('hidden');
        } catch (err) {
            alert("Gagal mengakses kamera: " + err);
        }
    }

    function resetScan() {
        document.getElementById('scanResult').classList.add('hidden');
        document.getElementById('cameraArea').classList.remove('hidden');
        document.getElementById('btnCapture').classList.remove('hidden');
        document.getElementById('btnReset').classList.add('hidden');
        document.getElementById('pdf_base64').value = "";
    }

    function processAndCrop() {
        // Tampilkan loading sebentar
        scannerStatus.innerText = "Processing AI...";

        let src = new cv.Mat(video.videoHeight, video.videoWidth, cv.CV_8UC4);
        let cap = new cv.VideoCapture(video);
        cap.read(src);

        let gray = new cv.Mat();
        cv.cvtColor(src, gray, cv.COLOR_RGBA2GRAY);
        cv.GaussianBlur(gray, gray, new cv.Size(5, 5), 0);
        
        let thresholded = new cv.Mat();
        cv.Canny(gray, thresholded, 75, 200);

        let contours = new cv.MatVector();
        let hierarchy = new cv.Mat();
        cv.findContours(thresholded, contours, hierarchy, cv.RETR_EXTERNAL, cv.CHAIN_APPROX_SIMPLE);

        let maxArea = 0;
        let maxContourIndex = -1;
        for (let i = 0; i < contours.size(); ++i) {
            let area = cv.contourArea(contours.get(i));
            if (area > maxArea) {
                maxArea = area;
                maxContourIndex = i;
            }
        }

        if (maxContourIndex !== -1) {
            let cnt = contours.get(maxContourIndex);
            let rect = cv.boundingRect(cnt);
            
            // Proses Crop ROI (Region of Interest)
            let dst = src.roi(rect);
            cv.imshow('canvasOutput', dst);

            // Generate PDF
            saveToPdf();

            // UI Switch
            document.getElementById('cameraArea').classList.add('hidden');
            document.getElementById('scanResult').classList.remove('hidden');
            document.getElementById('btnCapture').classList.add('hidden');
            document.getElementById('btnReset').classList.remove('hidden');
            scannerStatus.innerText = "Scan Berhasil!";
            
            dst.delete();
        } else {
            alert("AI tidak mendeteksi tepi kertas. Pastikan dokumen kontras dengan latar belakang!");
            scannerStatus.innerText = "AI Engine Ready!";
        }

        // Garbage Collection
        src.delete(); gray.delete(); thresholded.delete(); contours.delete(); hierarchy.delete();
    }

    function saveToPdf() {
        const { jsPDF } = window.jspdf;
        // Gunakan kompresi JPEG agar file tidak terlalu raksasa saat dikirim ke server
        const imgData = canvasOutput.toDataURL('image/jpeg', 0.8);
        const pdf = new jsPDF('p', 'mm', 'a4');
        
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = (canvasOutput.height * pdfWidth) / canvasOutput.width;
        
        pdf.addImage(imgData, 'JPEG', 0, 0, pdfWidth, pdfHeight);
        
        // Simpan string data ke input hidden
        const base64String = pdf.output('datauristring');
        document.getElementById('pdf_base64').value = base64String;
        console.log("PDF Base64 Generated!");
    }

    // Validasi sebelum submit
    document.getElementById('suratForm').onsubmit = function() {
        if (!document.getElementById('pdf_base64').value) {
            alert("Waduh bos, hasil scan belum ada! Scan dulu dokumennya.");
            return false;
        }
        return true;
    };
</script>
@endsection