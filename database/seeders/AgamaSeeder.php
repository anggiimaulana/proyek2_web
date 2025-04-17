<?php

namespace Database\Seeders;

use App\Models\Agama;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgamaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Agama
        Agama::create(['nama_agama' => 'Islam', 'created_at' => now(), 'updated_at' => now()]);    // ID = 1
        Agama::create(['nama_agama' => 'Kristen', 'created_at' => now(), 'updated_at' => now()]);  // ID = 2

    }
}
