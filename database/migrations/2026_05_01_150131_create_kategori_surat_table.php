<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_surat', function (Blueprint $table) {
            $table->id('id_kategori'); // Primary Key
            $table->string('kode_kategori'); //
            $table->string('nama_kategori'); //
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_surat');
    }
};