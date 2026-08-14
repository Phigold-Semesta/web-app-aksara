<?php

namespace App\Exports;

use App\Models\Surat;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SuratExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithCustomStartCell, WithEvents
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $filterTahun = $this->request->input('tahun');
        $filterBulan = $this->request->input('bulan');
        $filterKategori = $this->request->input('kategori');

        $query = Surat::with('kategori')->latest();

        if ($filterTahun) {
            $query->whereYear('tanggal_surat', $filterTahun);
        }

        if ($filterBulan) {
            $query->whereMonth('tanggal_surat', $filterBulan);
        }

        if ($filterKategori) {
            $query->where('id_kategori', $filterKategori);
        }

        return $query->get();
    }

    public function startCell(): string
    {
        return 'A6';
    }

    public function headings(): array
    {
        return [
            'NO',
            'NOMOR SURAT',
            'PERIHAL',
            'ASAL INSTANSI',
            'KATEGORI',
            'TANGGAL SURAT',
            'STATUS'
        ];
    }

    public function map($surat): array
    {
        static $no = 1;
        return [
            $no++,
            $surat->nomor_surat,
            $surat->perihal,
            $surat->asal_instansi,
            $surat->kategori->nama_kategori ?? '-',
            \Carbon\Carbon::parse($surat->tanggal_surat)->format('d-m-Y'),
            strtoupper($surat->status)
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->mergeCells('A1:G1');
        $sheet->mergeCells('A2:G2');
        $sheet->mergeCells('A3:G3');
        $sheet->mergeCells('A4:G4');

        $sheet->setCellValue('A1', 'AKSARA LPSE KABUPATEN KARAWANG');
        $sheet->setCellValue('A2', 'Laporan Analisis Statistik & Rekapitulasi Sirkulasi Surat');
        $sheet->setCellValue('A3', 'Tanggal Cetak: ' . date('d-m-Y'));
        
        $filterTahun = $this->request->input('tahun') ?: 'Semua Tahun';
        $filterBulanInput = $this->request->input('bulan');
        $bulanList = [1=>'Januari', 2=>'Februari', 3=>'Maret', 4=>'April', 5=>'Mei', 6=>'Juni', 7=>'Juli', 8=>'Agustus', 9=>'September', 10=>'Oktober', 11=>'November', 12=>'Desember'];
        $filterBulan = $filterBulanInput ? ($bulanList[$filterBulanInput] ?? $filterBulanInput) : 'Semua Bulan';
        
        $sheet->setCellValue('A4', 'Periode Filter: Tahun: ' . $filterTahun . ' | Bulan: ' . $filterBulan);

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14)->getColor()->setARGB('008F5D');
        $sheet->getStyle('A2')->getFont()->setBold(true)->setSize(10)->getColor()->setARGB('555555');
        $sheet->getStyle('A3')->getFont()->setSize(9)->getColor()->setARGB('777777');
        $sheet->getStyle('A4')->getFont()->setBold(true)->setSize(10)->getColor()->setARGB('333333');

        $sheet->getStyle('A1:A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('A6:G6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFF'],
                'size' => 10,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['argb' => '008F5D'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $lastRow = max($highestRow, 6);

                $sheet->getStyle('A6:G' . $lastRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'DDDDDD'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A7:A' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('F7:F' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('G7:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $sheet->getRowDimension(1)->setRowHeight(22);
                $sheet->getRowDimension(2)->setRowHeight(18);
                $sheet->getRowDimension(3)->setRowHeight(16);
                $sheet->getRowDimension(4)->setRowHeight(18);
                $sheet->getRowDimension(6)->setRowHeight(25);

                for ($i = 7; $i <= $lastRow; $i++) {
                    $sheet->getRowDimension($i)->setRowHeight(20);
                }

                foreach (range('A', 'G') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}