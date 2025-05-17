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
            'kk_id' => '1',
            'nama_kepala_keluarga' => 'John Doe',
            'nomor_telepon' => '1234567890',
            'password' => Hash::make('password'),
        ]);
    }
}
