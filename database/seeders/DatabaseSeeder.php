<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Admin
        User::create([
            'nama_lengkap' => 'Administrator Aksara',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'jabatan' => 'IT Support',
            'role' => 'admin',
        ]);

        // Buat Pimpinan
        User::create([
            'nama_lengkap' => 'Kepala LPSE',
            'username' => 'pimpinan',
            'password' => Hash::make('password'),
            'jabatan' => 'Kepala Bagian',
            'role' => 'pimpinan',
        ]);

        // Buat Petugas
        User::create([
            'nama_lengkap' => 'Petugas Helpdesk',
            'username' => 'petugas',
            'password' => Hash::make('password'),
            'jabatan' => 'Staff Administrasi',
            'role' => 'petugas',
        ]);
    }
}