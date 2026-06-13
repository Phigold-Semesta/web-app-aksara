<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstruksiDisposisi extends Model
{
    protected $table = 'instruksi_disposisi'; // Nama tabel
    protected $primaryKey = 'id_instruksi';   // Primary Key

    protected $fillable = [
        'nama_instruksi',
        'deskripsi', // Field baru ditambahkan agar bisa diisi (mass assignment)
    ];

    // Relasi: Satu instruksi baku digunakan di banyak Disposisi
    public function disposisi()
    {
        return $this->hasMany(Disposisi::class, 'id_instruksi', 'id_instruksi');
    }
}