<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriSurat extends Model
{
    protected $table = 'kategori_surat'; // Nama tabel singular
    protected $primaryKey = 'id_kategori';

    protected $fillable = [
        'kode_kategori',
        'nama_kategori',
    ];

    // Relasi: Satu kategori memiliki banyak Surat
    public function surat()
    {
        return $this->hasMany(Surat::class, 'id_kategori', 'id_kategori');
    }
}