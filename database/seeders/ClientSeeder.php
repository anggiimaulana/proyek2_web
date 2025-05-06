<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::factory()->create([
            'nik' => '1234567890',
            'name' => 'Anggi Maulana',
            'tempat_lahir' => 'Jakarta',
            'tanggal_lahir' => '2000-01-01',
            'jk' => 1,
            'status' => 1,
            'agama' => 1,
            'alamat' => 'Jl. Contoh',
            'pendidikan' => 1,
            'pekerjaan' => 1,
            'nomor_telepon' => '1234567890',
            'password' => Hash::make('password'),
        ]);
    }
}
