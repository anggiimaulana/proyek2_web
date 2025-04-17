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
        Schema::create('pengajuan_skp_pengajuan_bantuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hubungan')->constrained('hubungan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('nama');
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->foreignId('jk')->constrained('jenis_kelamin')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('agama')->constrained('agama')->onDelete('cascade')->onUpdate('cascade');
            $table->string('alamat');
            $table->foreignId('pekerjaan')->constrained('pekerjaan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('kategori_bantuan')->constrained('kategori_bantuan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('file_kk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_skp_pengajuan_bantuans');
    }
};
