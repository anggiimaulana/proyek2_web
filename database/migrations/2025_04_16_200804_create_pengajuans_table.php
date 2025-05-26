<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user_pengajuan')->nullable()->constrained('client')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_admin')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('kategori_pengajuan')->constrained('kategori_pengajuan')->onDelete('cascade')->onUpdate('cascade');
            $table->string('url_file')->nullable();
            $table->morphs('detail');
            $table->foreignId('status_pengajuan')->constrained('status_pengajuan')->onDelete('cascade')->onUpdate('cascade');
            $table->text('catatan')->nullable();
            $table->foreignId('id_admin_updated')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('id_kuwu_updated')->nullable()->constrained('kuwu')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
