<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id('id_disposisi'); // Primary Key
            $table->text('catatan_pimpinan'); //
            $table->dateTime('tanggal_disposisi'); //
            
            // Foreign Keys
            $table->foreignId('id_surat')->constrained('surat', 'id_surat');
            $table->foreignId('id_user')->constrained('user', 'id_user');
            $table->foreignId('id_instruksi')->constrained('instruksi_disposisi', 'id_instruksi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disposisi');
    }
};