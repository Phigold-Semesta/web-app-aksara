<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Data Surat - LPSE Kabupaten Karawang</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #333; }
        
        /* Header Title */
        .header { text-align: center; border-bottom: 2px solid #006B43; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #006B43; text-transform: uppercase; font-size: 18px; }
        .header h3 { margin: 5px 0 0 0; font-size: 14px; }

        /* Table */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #006B43; color: white; text-transform: uppercase; font-size: 10px; }
        
        /* Signature Section */
        .signature-section { margin-top: 50px; width: 100%; }
        .signature-box { float: right; width: 300px; text-align: center; }
        .signature-space { height: 80px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>LPSE KABUPATEN KARAWANG</h1>
        <h3>LAPORAN DATA SURAT</h3>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nomor Surat</th>
                <th>Asal Instansi</th>
                <th>Perihal</th>
                <th>Tanggal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($surats as $surat)
            <tr>
                <td>{{ $surat->nomor_surat }}</td>
                <td>{{ $surat->asal_instansi }}</td>
                <td>{{ $surat->perihal }}</td>
                <td>{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d-m-Y') }}</td>
                <td>{{ strtoupper($surat->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <p>Karawang, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <p>Kepala Bagian UKPBJ Karawang</p>
            <div class="signature-space"></div>
            <p><strong>__________________________</strong></p>
            <p>(WAHYU E PRASETYO,ST.,MM)</p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>