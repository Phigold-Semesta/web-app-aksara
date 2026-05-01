<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Surat extends Model
{
    protected $table = 'surat'; // Nama tabel singular
    protected $primaryKey = 'id_surat';

    protected $fillable = [
        'nomor_surat',
        'perihal',
        'asal_instansi',
        'tanggal_surat',
        'tanggal_terima',
        'file_surat',
        'status',
        'id_user',
        'id_kategori',
    ];

    // Relasi: Surat dibuat oleh satu User (Admin/Petugas)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi: Surat memiliki satu Kategori
    public function kategori_surat()
    {
        return $this->belongsTo(KategoriSurat::class, 'id_kategori', 'id_kategori');
    }

    // Relasi: Surat bisa memiliki banyak Disposisi
    public function disposisi()
    {
        return $this->hasMany(Disposisi::class, 'id_surat', 'id_surat');
    }

    // Relasi: Surat memiliki satu Arsip (One-to-One)
    public function arsip()
    {
        return $this->hasOne(Arsip::class, 'id_surat', 'id_surat');
    }
}