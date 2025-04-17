<?php

namespace Database\Seeders;

use App\Models\Pekerjaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Pekerjaan
        Pekerjaan::create(['nama_pekerjaan' => 'PNS', 'created_at' => now(), 'updated_at' => now()]);
        Pekerjaan::create(['nama_pekerjaan' => 'Petani', 'created_at' => now(), 'updated_at' => now()]);

    }
}
