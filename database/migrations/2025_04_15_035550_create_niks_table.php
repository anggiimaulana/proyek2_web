<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('niks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_nik')->unique();
            $table->foreignId('kk_id')->constrained('kartu_keluargas')->onDelete('cascade')->onUpdate('cascade');
            $table->string('name');
            $table->string('tempat_lahir');
            $table->foreignId('hubungan')->constrained('hubungan')->onDelete('cascade')->onUpdate('cascade');
            $table->date('tanggal_lahir');
            $table->foreignId('jk')->constrained('jenis_kelamin')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('status')->constrained('status_perkawinan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('agama')->constrained('agama')->onDelete('cascade')->onUpdate('cascade');
            $table->string('alamat');
            $table->foreignId('pendidikan')->constrained('pendidikan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('pekerjaan')->constrained('pekerjaan')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('niks');
    }
};
