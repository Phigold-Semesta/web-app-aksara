<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat', function (Blueprint $table) {
            $table->id('id_surat'); // Primary Key
            $table->string('nomor_surat'); //
            $table->string('perihal'); //
            $table->string('asal_instansi'); //
            $table->date('tanggal_surat'); //
            $table->date('tanggal_terima'); //
            $table->string('file_surat'); // Atribut untuk foto/scan
            $table->string('status'); //
            
            // Foreign Keys ke tabel user dan kategori_surat
            $table->foreignId('id_user')->constrained('user', 'id_user');
            $table->foreignId('id_kategori')->constrained('kategori_surat', 'id_kategori');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};