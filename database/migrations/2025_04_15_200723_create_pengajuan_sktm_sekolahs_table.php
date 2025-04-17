<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan_sktm_sekolah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hubungan')->constrained('hubungan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama_ortu');
            $table->string('nama_anak');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->foreignId('jk')->constrained('jenis_kelamin')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('agama')->constrained('agama')->onDelete('cascade')->onUpdate('cascade');
            $table->string('asal_sekolah');
            $table->string('kelas');
            $table->string('file_kk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_sktm_sekolahs');
    }
};
