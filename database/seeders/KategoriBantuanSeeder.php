<?php

namespace Database\Seeders;

use App\Models\KategoriBantuan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriBantuanSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Pendidikan
        KategoriBantuan::create(['nama_kategori' => 'PKH', 'created_at' => now(), 'updated_at' => now()]);
        KategoriBantuan::create(['nama_kategori' => 'Bansos', 'created_at' => now(), 'updated_at' => now()]);
    }
}
