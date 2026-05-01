<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_log', function (Blueprint $table) {
            $table->id('id_log'); // Primary Key
            $table->string('aktivitas'); //
            $table->text('deskripsi'); //
            $table->string('ip_address'); //
            $table->dateTime('waktu_kejadian'); //
            
            // Foreign Key
            $table->foreignId('id_user')->constrained('user', 'id_user');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_log');
    }
};