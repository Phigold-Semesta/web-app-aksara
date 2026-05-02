<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SuratSeeder extends Seeder
{
    public function run(): void
    {
        // Nonaktifkan foreign key check agar bisa truncate tabel tanpa error relasi
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('surat')->truncate();
        DB::table('kategori_surat')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Seed Kategori Surat (DITAMBAHKAN: kode_kategori)
        $kategoriIds = [];
        $kategoris = [
            ['nama_kategori' => 'SURAT DINAS', 'kode' => 'SRD'],
            ['nama_kategori' => 'SURAT KEPUTUSAN', 'kode' => 'SKP'],
            ['nama_kategori' => 'UNDANGAN', 'kode' => 'UND'],
            ['nama_kategori' => 'SURAT TUGAS', 'kode' => 'STG'],
        ];

        foreach ($kategoris as $k) {
            $kategoriIds[] = DB::table('kategori_surat')->insertGetId([
                'nama_kategori' => $k['nama_kategori'],
                'kode_kategori' => $k['kode'], // Menambahkan kolom yang menyebabkan error
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 2. Seed Data Surat
        $dataSurat = [
            [
                'nomor_surat' => '001/SK/AKS/2026',
                'perihal' => 'Permohonan Kerjasama Digitalisasi',
                'asal_instansi' => 'Dinas Pendidikan Kota',
                'tanggal_surat' => '2026-04-10',
                'tanggal_terima' => '2026-04-11',
                'id_kategori' => $kategoriIds[0],
                'status' => 'selesai',
            ],
            [
                'nomor_surat' => '052/UND/PROV/2026',
                'perihal' => 'Undangan Rapat Koordinasi Arsip',
                'asal_instansi' => 'Sekretariat Daerah',
                'tanggal_surat' => '2026-04-15',
                'tanggal_terima' => '2026-04-16',
                'id_kategori' => $kategoriIds[2],
                'status' => 'pending',
            ],
            [
                'nomor_surat' => '010/ST/BPN/2026',
                'perihal' => 'Surat Tugas Survey Lokasi',
                'asal_instansi' => 'Badan Pertanahan Nasional',
                'tanggal_surat' => '2026-04-20',
                'tanggal_terima' => '2026-04-21',
                'id_kategori' => $kategoriIds[3],
                'status' => 'selesai',
            ],
            [
                'nomor_surat' => '088/EXT/KEMEN/2026',
                'perihal' => 'Laporan Akuntabilitas Tahunan',
                'asal_instansi' => 'Kementerian Dalam Negeri',
                'tanggal_surat' => '2026-04-25',
                'tanggal_terima' => '2026-04-26',
                'id_kategori' => $kategoriIds[1],
                'status' => 'pending',
            ],
            [
                'nomor_surat' => '005/AKS/V/2026',
                'perihal' => 'Penawaran Perangkat Server',
                'asal_instansi' => 'PT. Teknologi Maju',
                'tanggal_surat' => '2026-05-01',
                'tanggal_terima' => '2026-05-01',
                'id_kategori' => $kategoriIds[0],
                'status' => 'selesai',
            ],
            [
                'nomor_surat' => '009/OFF/AKS/2026',
                'perihal' => 'Pemberitahuan Rekayasa Lalu Lintas',
                'asal_instansi' => 'Dinas Perhubungan',
                'tanggal_surat' => '2026-05-02',
                'tanggal_terima' => '2026-05-02',
                'id_kategori' => $kategoriIds[0],
                'status' => 'pending',
            ],
        ];

        foreach ($dataSurat as $s) {
            DB::table('surat')->insert(array_merge($s, [
                'id_user' => 1, 
                'file_surat' => 'dummy_file.pdf', 
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}