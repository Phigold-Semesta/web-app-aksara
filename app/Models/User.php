<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user'; // Nama tabel singular
    protected $primaryKey = 'id_user'; // PK sesuai ERD

    protected $fillable = [
        'nama_lengkap',
        'username',
        'password',
        'jabatan',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi: User mengelola banyak Surat
    public function surat()
    {
        return $this->hasMany(Surat::class, 'id_user', 'id_user');
    }

    // Relasi: User (Pimpinan) memberikan banyak Disposisi
    public function disposisi()
    {
        return $this->hasMany(Disposisi::class, 'id_user', 'id_user');
    }

    // Relasi: User melakukan banyak aktivitas di Audit Log
    public function audit_log()
    {
        return $this->hasMany(AuditLog::class, 'id_user', 'id_user');
    }
}