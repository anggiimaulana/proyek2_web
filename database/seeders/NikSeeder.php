<?php

namespace Database\Seeders;

use App\Models\Nik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NikSeeder extends Seeder
{
    public function run(): void
    {
        Nik::create([
            'nomor_nik' => '123456789', 
            'name' => 'Anggi Maulana',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'jk' => 1,
            'status' => 1,
            'agama' => 1,
            'alamat' => 'Jl. Contoh',
            'pendidikan' => 1,
            'pekerjaan' => 1,
            'hubungan' => 1,
            'kk_id' => 1, 'created_at' => now(), 
            'updated_at' => now()
        ]);
    }
}
