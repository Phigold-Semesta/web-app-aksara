<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';

    // Menegaskan bahwa PK adalah integer dan auto-incrementing
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'nama_lengkap',
        'username',
        'password',
        'jabatan',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Relasi: User mengelola banyak Surat
     */
    public function surat(): HasMany
    {
        return $this->hasMany(Surat::class, 'id_user', 'id_user');
    }

    /**
     * Relasi: User memberikan banyak Disposisi
     */
    public function disposisi(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'id_user', 'id_user');
    }

    /**
     * Relasi: User melakukan banyak aktivitas di Audit Log
     */
    public function audit_log(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'id_user', 'id_user');
    }
}