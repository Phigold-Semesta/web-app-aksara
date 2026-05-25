<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriSurat extends Model
{
    /**
     * Nama tabel di database.
     */
    protected $table = 'kategori_surat';

    /**
     * Primary key kustom sesuai struktur database Anda.
     */
    protected $primaryKey = 'id_kategori';

    /**
     * Memastikan ID bisa bertambah (incrementing).
     */
    public $incrementing = true;

    /**
     * Tipe data primary key.
     */
    protected $keyType = 'int';

    /**
     * PERBAIKAN: Menambahkan 'keterangan' ke dalam $fillable agar bisa diisi.
     */
    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
        'keterangan', 
    ];

    /**
     * Relasi: Satu kategori memiliki banyak Surat.
     */
    public function surat()
    {
        return $this->hasMany(Surat::class, 'id_kategori', 'id_kategori');
    }
}