<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'nip' => '1234567890',
            'name' => 'Erwan Wijaya',
            'jk' => 1, // Laki-laki
            'status' => 1, // Belum Menikah
            'agama' => 1, // Islam
            'email' => 'test@example.com',
            'password' => Hash::make('123'),
        ]);
    }
}
