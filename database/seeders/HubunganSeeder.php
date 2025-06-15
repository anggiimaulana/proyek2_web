<?php

namespace Database\Seeders;

use App\Models\Hubungan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HubunganSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Pendidikan
        Hubungan::create(['jenis_hubungan' => 'Kepala Keluarga', 'created_at' => now(), 'updated_at' => now()]);
        Hubungan::create(['jenis_hubungan' => 'Istri', 'created_at' => now(), 'updated_at' => now()]);
        Hubungan::create(['jenis_hubungan' => 'Saudara Kandung', 'created_at' => now(), 'updated_at' => now()]);
        Hubungan::create(['jenis_hubungan' => 'Anak', 'created_at' => now(), 'updated_at' => now()]);
    }
}
