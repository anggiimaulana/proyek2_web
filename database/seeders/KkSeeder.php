<?php

namespace Database\Seeders;

use App\Models\KartuKeluarga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        KartuKeluarga::create(['nomor_kk' => '123456', 'kepala_keluarga' => 'samsul', 'created_at' => now(), 'updated_at' => now()]);   // ID = 1

    }
}
