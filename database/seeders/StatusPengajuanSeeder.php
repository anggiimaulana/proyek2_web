<?php

namespace Database\Seeders;

use App\Models\StatusPengajuan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusPengajuanSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Jenis Kelamin
        StatusPengajuan::create(['status' => 'Diserahkan', 'created_at' => now(), 'updated_at' => now()]);   // ID = 1
        StatusPengajuan::create(['status' => 'Diproses', 'created_at' => now(), 'updated_at' => now()]);
        StatusPengajuan::create(['status' => 'Ditolak', 'created_at' => now(), 'updated_at' => now()]);
        StatusPengajuan::create(['status' => 'Disetujui', 'created_at' => now(), 'updated_at' => now()]);
        StatusPengajuan::create(['status' => 'Direvisi', 'created_at' => now(), 'updated_at' => now()]);
    }
}
