<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kk_id')->constrained('kartu_keluargas')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_kepala_keluarga');
            $table->string('nomor_telepon')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client');
    }
};
