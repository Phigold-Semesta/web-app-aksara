<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arsip', function (Blueprint $table) {
            $table->id('id_arsip'); // Primary Key
            $table->string('lokasi_fisik'); //
            $table->date('tanggal_arsip'); //
            $table->string('masa_retensi'); //
            $table->string('status_retensi'); //
            
            // Foreign Key
            $table->foreignId('id_surat')->constrained('surat', 'id_surat');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip');
    }
};