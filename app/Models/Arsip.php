<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    protected $table = 'arsip'; 
    protected $primaryKey = 'id_arsip';

    // Memastikan Laravel tahu bahwa ID ini adalah auto-incrementing
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'lokasi_fisik',
        'tanggal_arsip',
        'masa_retensi',
        'status_retensi',
        'id_surat',
    ];

    /**
     * PENYEMPURNAAN: 
     * Menambahkan $casts agar masa_retensi dan tanggal_arsip otomatis 
     * menjadi objek Carbon. Dengan ini, Anda bisa langsung menggunakan 
     * $arsip->masa_retensi->format('d M Y') di Blade tanpa error.
     */
    protected $casts = [
        'tanggal_arsip' => 'date',
        'masa_retensi'  => 'date',
    ];

    // Relasi: Arsip merujuk pada satu Surat
    public function surat()
    {
        return $this->belongsTo(Surat::class, 'id_surat', 'id_surat');
    }
}