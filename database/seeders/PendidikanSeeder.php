<?php

namespace Database\Seeders;

use App\Models\Pendidikan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Pendidikan
        Pendidikan::create(['jenis_pendidikan' => 'SD', 'created_at' => now(), 'updated_at' => now()]);
        Pendidikan::create(['jenis_pendidikan' => 'SMP', 'created_at' => now(), 'updated_at' => now()]);
    }
}
