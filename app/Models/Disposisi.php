<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disposisi extends Model
{
    protected $table = 'disposisi'; // Nama tabel singular
    protected $primaryKey = 'id_disposisi';

    protected $fillable = [
        'catatan_pimpinan',
        'tanggal_disposisi',
        'id_surat',
        'id_user',
        'id_instruksi',
    ];

    // Relasi: Disposisi merujuk pada satu Surat
    public function surat()
    {
        return $this->belongsTo(Surat::class, 'id_surat', 'id_surat');
    }

    // Relasi: Disposisi diberikan oleh satu User (Pimpinan)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi: Disposisi mengacu pada satu Instruksi Baku
    public function instruksi_disposisi()
    {
        return $this->belongsTo(InstruksiDisposisi::class, 'id_instruksi', 'id_instruksi');
    }
}