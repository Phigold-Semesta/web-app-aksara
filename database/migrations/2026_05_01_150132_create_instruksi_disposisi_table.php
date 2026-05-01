<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('instruksi_disposisi', function (Blueprint $table) {
            $table->id('id_instruksi'); // Primary Key
            $table->string('nama_instruksi'); //
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('instruksi_disposisi');
    }
};