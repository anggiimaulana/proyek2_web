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
        Schema::create('client', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->unique();
            $table->string('name');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->foreignId('jk')->constrained('jenis_kelamin')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('status')->constrained('status_perkawinan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('agama')->constrained('agama')->onDelete('cascade')->onUpdate('cascade');
            $table->string('alamat');
            $table->foreignId('pendidikan')->constrained('pendidikan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('pekerjaan')->constrained('pekerjaan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nomor_telepon')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client');
    }
};
