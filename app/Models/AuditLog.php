<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_log'; // Nama tabel singular
    protected $primaryKey = 'id_log';

    protected $fillable = [
        'aktivitas',
        'deskripsi',
        'ip_address',
        'waktu_kejadian',
        'id_user',
    ];

    // Relasi: Log mencatat aktivitas satu User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}