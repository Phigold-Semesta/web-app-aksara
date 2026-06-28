<?php

namespace App\Exports;

use App\Models\Surat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

class SuratExport implements FromCollection, WithHeadings, WithEvents
{
    public function collection()
    {
        return Surat::select('nomor_surat', 'asal_instansi', 'perihal', 'tanggal_surat', 'status')->get();
    }

    public function headings(): array
    {
        return ['Nomor Dokumen', 'Asal Instansi', 'Perihal', 'Tanggal', 'Status'];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // 1. Tambahkan 2 baris kosong di atas untuk Judul & Dinas
                $event->sheet->insertNewRowBefore(1, 2);
                
                // 2. Isi Judul & Nama Dinas
                $event->sheet->setCellValue('A1', 'LPSE KABUPATEN KARAWANG');
                $event->sheet->setCellValue('A2', 'LAPORAN DATA SURAT');
                
                // 3. Merge Cell agar judul rapi di tengah
                $event->sheet->mergeCells('A1:E1');
                $event->sheet->mergeCells('A2:E2');
                
                // 4. Styling Judul
                $event->sheet->getStyle('A1:E2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A1:E2')->getFont()->setBold(true)->setSize(12);

                // 5. Styling Header (Baris ke-3 karena sudah digeser 2 baris tadi)
                $headerStyle = [
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '006B43'] // Emerald Green
                    ],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
                ];
                $event->sheet->getStyle('A3:E3')->applyFromArray($headerStyle);

                // 6. Memberi Border pada seluruh tabel
                $highestRow = $event->sheet->getHighestRow();
                $cellRange = 'A3:E' . $highestRow;
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);

                // 7. Auto size kolom agar rapi
                $event->sheet->getColumnDimension('A')->setAutoSize(true);
                $event->sheet->getColumnDimension('B')->setAutoSize(true);
                $event->sheet->getColumnDimension('C')->setAutoSize(true);
                $event->sheet->getColumnDimension('D')->setAutoSize(true);
                $event->sheet->getColumnDimension('E')->setAutoSize(true);
            },
        ];
    }
}