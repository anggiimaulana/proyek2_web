<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_skpot_beasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hubungan')->constrained('hubungan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama');
            $table->foreignId('kk_id')->constrained('kartu_keluargas')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('nik_id')->constrained('niks')->onDelete('cascade')->onUpdate('cascade');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->foreignId('jk')->constrained('jenis_kelamin')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('agama')->constrained('agama')->onDelete('cascade')->onUpdate('cascade');
            $table->string('alamat');
            $table->string('nama_ortu');
            $table->foreignId('pekerjaan')->constrained('pekerjaan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('penghasilan')->constrained('penghasilan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('file_kk');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_skpot_beasiswas');
    }
};
