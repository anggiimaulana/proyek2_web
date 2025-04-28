<?php

namespace Database\Seeders;

use App\Models\Agama;
use App\Models\Client;
use App\Models\JenisKelamin;
use App\Models\Pekerjaan;
use App\Models\Pendidikan;
use App\Models\StatusPerkawinan;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            JkSeeder::class,
            StatusPerkawinanSeeder::class,
            AgamaSeeder::class,
            PekerjaanSeeder::class,
            PendidikanSeeder::class,
            HubunganSeeder::class,
            StatusPengajuanSeeder::class,
            KategoriBantuanSeeder::class,
            KategoriPengajuanSeeder::class,
            PenghasilanSeeder::class,
            ClientSeeder::class,
            UserSeeder::class,
            KuwuSeeder::class,
        ]);
    }
}
