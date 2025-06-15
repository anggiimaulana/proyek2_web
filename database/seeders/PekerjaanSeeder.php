<?php

namespace Database\Seeders;

use App\Models\Pekerjaan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PekerjaanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pekerjaanList = [
            'Belum/Tidak Bekerja',
            'Pelajar/Mahasiswa',
            'Mengurus Rumah Tangga',
            'PNS',
            'TNI',
            'POLRI',
            'Karyawan Swasta',
            'Karyawan BUMN',
            'Karyawan Honorer',
            'Guru',
            'Dosen',
            'Dokter',
            'Perawat',
            'Bidan',
            'Pedagang',
            'Petani/Pekebun',
            'Nelayan',
            'Buruh Harian Lepas',
            'Buruh Tani',
            'Buruh Pabrik',
            'Sopir',
            'Montir',
            'Wiraswasta',
            'Wirausaha',
            'Pensiunan',
            'Notaris',
            'Pengacara/Advokat',
            'Arsitek',
            'Akuntan',
            'Seniman/Artis',
            'Peneliti',
            'Penyiar',
            'Wartawan',
            'Ustadz/Pendeta/Pemuka Agama',
            'Tukang Cukur',
            'Tukang Kayu',
            'Tukang Batu',
            'Tukang Las/Pandai Besi',
            'Tukang Jahit',
            'Pramugari/Pramugara',
            'Masinis',
            'Pilot',
            'Nelayan/Perikanan',
            'Pialang',
            'Pengusaha',
            'Konsultan',
            'Dukun Tradisional',
            'Imam Masjid',
            'Pendeta',
            'Bhiksu',
            'Pastor',
            'Santri',
            'Ibu Rumah Tangga',
        ];

        foreach ($pekerjaanList as $pekerjaan) {
            Pekerjaan::create([
                'nama_pekerjaan' => $pekerjaan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
