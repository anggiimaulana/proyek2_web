<?php

namespace Database\Seeders;

use App\Models\Kuwu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KuwuSeeder extends Seeder
{
    public function run(): void
    {
        Kuwu::factory()->create([
            'nip' => '1234567890',
            'nama' => 'Bayu Kurniawan',
            'jk' => 1, 
            'status' => 1,
            'agama' => 1,
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
