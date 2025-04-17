<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuwu', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->string('nama');
            $table->foreignId('jk')->constrained('jenis_kelamin')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('status')->constrained('status_perkawinan')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('agama')->constrained('agama')->onDelete('cascade')->onUpdate('cascade');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuwus');
    }
};
