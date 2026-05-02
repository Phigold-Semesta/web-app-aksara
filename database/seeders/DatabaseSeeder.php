<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

    $this->call([
        // UserSeeder::class, // Jika Anda punya seeder user, jalankan ini dulu
        SuratSeeder::class,
    ]);
    }
        
}