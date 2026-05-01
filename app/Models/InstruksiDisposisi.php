<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstruksiDisposisi extends Model
{
    protected $table = 'instruksi_disposisi'; // Nama tabel singular
    protected $primaryKey = 'id_instruksi';

    protected $fillable = [
        'nama_instruksi',
    ];

    // Relasi: Satu instruksi baku digunakan di banyak Disposisi
    public function disposisi()
    {
        return $this->hasMany(Disposisi::class, 'id_instruksi', 'id_instruksi');
    }
}