<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    protected $table = 'arsip'; // Nama tabel singular
    protected $primaryKey = 'id_arsip';

    protected $fillable = [
        'lokasi_fisik',
        'tanggal_arsip',
        'masa_retensi',
        'status_retensi',
        'id_surat',
    ];

    // Relasi: Arsip merujuk pada satu Surat
    public function surat()
    {
        return $this->belongsTo(Surat::class, 'id_surat', 'id_surat');
    }
}