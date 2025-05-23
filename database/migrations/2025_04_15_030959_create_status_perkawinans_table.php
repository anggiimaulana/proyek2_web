<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('status_perkawinan', function (Blueprint $table) {
            $table->id();
            $table->string('status_perkawinan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('status_perkawinans');
    }
};
