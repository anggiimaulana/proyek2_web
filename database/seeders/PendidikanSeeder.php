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
        $pendidikanList = [
            'Tidak/Belum Sekolah',
            'Belum Tamat SD/Sederajat',
            'Tamat SD/Sederajat',
            'SLTP/Sederajat',
            'SLTA/Sederajat',
            'D1',
            'D2',
            'D3',
            'D4/S1',
            'S2',
            'S3',
        ];

        foreach ($pendidikanList as $pendidikan) {
            Pendidikan::create([
                'jenis_pendidikan' => $pendidikan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
