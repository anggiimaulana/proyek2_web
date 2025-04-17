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
        Schema::create('pengajuan_sk_usaha', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hubungan')->constrained('hubungan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nik');
            $table->string('nama');
            $table->foreignId('jk')->constrained('jenis_kelamin')->onDelete('cascade')->onUpdate('cascade');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->foreignId('status_perkawinan')->constrained('status_perkawinan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('pekerjaan')->constrained('pekerjaan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('alamat');
            $table->string('nama_usaha');
            $table->string('file_ktp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_sk_usaha');
    }
};
