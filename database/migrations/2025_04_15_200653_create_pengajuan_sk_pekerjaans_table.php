<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_sk_pekerjaans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hubungan')->constrained('hubungan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama');
            $table->foreignId('kk_id')->constrained('kartu_keluargas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('nik_id')->constrained('niks')->onDelete('cascade')->onUpdate('cascade');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->foreignId('jk')->constrained('jenis_kelamin')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('status_perkawinan')->constrained('status_perkawinan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('pekerjaan_terdahulu')->constrained('pekerjaan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('pekerjaan_sekarang')->constrained('pekerjaan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('alamat');
            $table->string('file_kk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_sk_pekerjaans');
    }
};
