<?php

namespace Database\Seeders;

use App\Models\Penghasilan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenghasilanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Jenis Kelamin
        Penghasilan::create(['rentang_penghasilan' => 'Rp.5.000.000 - Rp.10.000.000', 'created_at' => now(), 'updated_at' => now()]);   // ID = 1
        Penghasilan::create(['rentang_penghasilan' => 'Rp.10.000.000 - Rp.15.000.000', 'created_at' => now(), 'updated_at' => now()]);
    
    }
}
