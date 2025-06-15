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
        Agama::create(['nama_agama' => 'Kristen Protestan', 'created_at' => now(), 'updated_at' => now()]);  // ID = 2
        Agama::create(['nama_agama' => 'Kristen Khatolik', 'created_at' => now(), 'updated_at' => now()]);  // ID = 2
        Agama::create(['nama_agama' => 'Budha', 'created_at' => now(), 'updated_at' => now()]);  // ID = 2
        Agama::create(['nama_agama' => 'Hindu', 'created_at' => now(), 'updated_at' => now()]);  // ID = 2
        Agama::create(['nama_agama' => 'Konghuchu', 'created_at' => now(), 'updated_at' => now()]);  // ID = 2

    }
}
