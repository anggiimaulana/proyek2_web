<?php

namespace Database\Seeders;

use App\Models\JenisKelamin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class JkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Jenis Kelamin
        JenisKelamin::create(['jenis_kelamin' => 'Laki-laki', 'created_at' => now(), 'updated_at' => now()]);   // ID = 1
        JenisKelamin::create(['jenis_kelamin' => 'Perempuan', 'created_at' => now(), 'updated_at' => now()]);
    }
}
