<?php

namespace Database\Seeders;

use App\Models\StatusPerkawinan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusPerkawinanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Status Perkawinan
        StatusPerkawinan::create(['status_perkawinan' => 'Belum Menikah', 'created_at' => now(), 'updated_at' => now()]); // ID = 1
        StatusPerkawinan::create(['status_perkawinan' => 'Menikah', 'created_at' => now(), 'updated_at' => now()]);       // ID = 2

    }
}
