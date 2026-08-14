<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan Sirkulasi Surat AKSARA</title>
    <style>
        body { font-family: sans-serif; color: #333; font-size: 11px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #008f5d; padding-bottom: 10px; }
        .header h2 { color: #008f5d; margin: 0; text-transform: uppercase; font-size: 14px; }
        .header p { margin: 4px 0; color: #666; font-size: 10px; }
        .info-filter { margin-bottom: 15px; font-weight: bold; font-size: 11px; color: #444; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background-color: #008f5d; color: white; font-size: 10px; text-transform: uppercase; }
        tr:nth-child(even) { background-color: #f9f9f9; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h2>AKSARA LPSE KABUPATEN KARAWANG</h2>
        <p>Laporan Analisis Statistik & Rekapitulasi Sirkulasi Surat</p>
        <p>Tanggal Cetak: {{ date('d-m-Y') }}</p>
    </div>

    <div class="info-filter">
        Periode Filter: 
        Tahun: {{ $filterTahun ?: 'Semua Tahun' }} | 
        Bulan: {{ $filterBulan ? \Carbon\Carbon::create()->month($filterBulan)->translatedFormat('F') : 'Semua Bulan' }}
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Surat</th>
                <th>Perihal</th>
                <th>Asal Instansi</th>
                <th>Kategori</th>
                <th>Tanggal Surat</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suratList as $index => $surat)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $surat->nomor_surat }}</td>
                    <td>{{ $surat->perihal }}</td>
                    <td>{{ $surat->asal_instansi }}</td>
                    <td>{{ $surat->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d-m-Y') }}</td>
                    <td>{{ $surat->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #777;">Tidak ada data surat yang sesuai dengan filter pilihan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>