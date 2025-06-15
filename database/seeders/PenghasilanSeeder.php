<?php

namespace Database\Seeders;

use App\Models\Penghasilan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PenghasilanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $penghasilanList = [
            'Tidak Berpenghasilan',
            'Kurang dari Rp.500.000',
            'Rp.500.000 - Rp.999.999',
            'Rp.1.000.000 - Rp.1.999.999',
            'Rp.2.000.000 - Rp.4.999.999',
            'Rp.5.000.000 - Rp.10.000.000',
            'Rp.10.000.000 - Rp.15.000.000',
            'Rp.15.000.000 - Rp.20.000.000',
            'Lebih dari Rp.20.000.000'
        ];

        foreach ($penghasilanList as $rentang) {
            Penghasilan::create([
                'rentang_penghasilan' => $rentang,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
