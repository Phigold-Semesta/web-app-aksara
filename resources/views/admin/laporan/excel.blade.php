<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Sirkulasi Surat AKSARA</title>
</head>
<body>
    <table>
        <tr>
            <th colspan="7" style="font-size: 14px; font-weight: bold; text-align: center;">AKSARA LPSE KABUPATEN KARAWANG</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; color: #666;">Laporan Rekapitulasi Sirkulasi Surat</th>
        </tr>
        <tr>
            <th colspan="7" style="text-align: center; color: #666;">Periode Tahun: {{ $filterTahun ?: 'Semua Tahun' }} | Bulan: {{ $filterBulan ?: 'Semua Bulan' }}</th>
        </tr>
        <tr><th colspan="7"></th></tr>
        <thead>
            <tr>
                <th style="background-color: #008f5d; color: #ffffff; font-weight: bold; border: 1px solid #000;">No</th>
                <th style="background-color: #008f5d; color: #ffffff; font-weight: bold; border: 1px solid #000;">Nomor Surat</th>
                <th style="background-color: #008f5d; color: #ffffff; font-weight: bold; border: 1px solid #000;">Perihal</th>
                <th style="background-color: #008f5d; color: #ffffff; font-weight: bold; border: 1px solid #000;">Asal Instansi</th>
                <th style="background-color: #008f5d; color: #ffffff; font-weight: bold; border: 1px solid #000;">Kategori</th>
                <th style="background-color: #008f5d; color: #ffffff; font-weight: bold; border: 1px solid #000;">Tanggal Surat</th>
                <th style="background-color: #008f5d; color: #ffffff; font-weight: bold; border: 1px solid #000;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suratList as $index => $surat)
                <tr>
                    <td style="border: 1px solid #ccc; text-align: center;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid #ccc;">{{ $surat->nomor_surat }}</td>
                    <td style="border: 1px solid #ccc;">{{ $surat->perihal }}</td>
                    <td style="border: 1px solid #ccc;">{{ $surat->asal_instansi }}</td>
                    <td style="border: 1px solid #ccc;">{{ $surat->kategori->nama_kategori ?? '-' }}</td>
                    <td style="border: 1px solid #ccc; text-align: center;">{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d-m-Y') }}</td>
                    <td style="border: 1px solid #ccc; text-align: center;">{{ $surat->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="border: 1px solid #ccc; text-align: center;">Tidak ada data surat yang sesuai dengan filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>