<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_sktm_beasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hubungan')->constrained('hubungan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_anak');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('suku');
            $table->foreignId('agama')->constrained('agama')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('jk')->constrained('jenis_kelamin')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('pekerjaan_anak')->constrained('pekerjaan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama');
            $table->string('nama_ibu');
            $table->foreignId('pekerjaan_ortu')->constrained('pekerjaan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('file_kk');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_sktm_beasiswas');
    }
};
