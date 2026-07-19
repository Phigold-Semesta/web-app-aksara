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

    // Properti fillable sudah mencakup semua penyesuaian terbaru
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
     */
    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSurat::class, 'id_kategori', 'id_kategori');
    }

    /**
     * Relasi ke Model User
     */
    public function user(): BelongsTo
    {
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

    /**
     * Relasi ke Model InstruksiPimpinan (DITAMBAHKAN)
     * Digunakan untuk mengecek status disposisi/instruksi secara spesifik
     */
    public function instruksi(): HasOne
    {
        return $this->hasOne(InstruksiDisposisi::class, 'id_surat', 'id_surat');
    }
}