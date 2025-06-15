<?php

namespace Database\Seeders;

use App\Models\StatusPerkawinan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusPerkawinanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statusList = [
            'Belum Kawin',
            'Kawin',
            'Cerai Hidup',
            'Cerai Mati',
        ];

        foreach ($statusList as $status) {
            StatusPerkawinan::create([
                'status_perkawinan' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
