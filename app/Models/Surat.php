<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Surat extends Model
{
    use HasFactory;

    protected $table = 'surat';
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

    /**
     * Relasi ke Model KategoriSurat
     * Digunakan oleh: Surat::with('kategori')
     */
    public function kategori(): BelongsTo
    {
        // Parameter 2: Foreign Key di tabel surat
        // Parameter 3: Primary Key di tabel kategori_surat
        return $this->belongsTo(KategoriSurat::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Relasi ke Model User
     * Digunakan oleh: Surat::with('user')
     */
    public function user(): BelongsTo
    {
        // PERBAIKAN: Parameter ketiga harus 'id_user' karena tabel user tidak punya kolom 'id'
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    /**
     * Relasi ke Model Disposisi
     */
    public function disposisi(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'id_surat', 'id_surat');
    }

    /**
     * Relasi ke Model Arsip
     */
    public function arsip(): HasOne
    {
        return $this->hasOne(Arsip::class, 'id_surat', 'id_surat');
    }
}