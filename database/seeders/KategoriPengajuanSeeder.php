<?php

namespace Database\Seeders;

use App\Models\KategoriPengajuan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriPengajuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Seed Pendidikan
        KategoriPengajuan::create(['nama_kategori' => 'Surat Keterangan Tidak Mampu (Listrik)', 'created_at' => now(), 'updated_at' => now()]);
        KategoriPengajuan::create(['nama_kategori' => 'Surat Keterangan Tidak Mampu (Beasiswa)', 'created_at' => now(), 'updated_at' => now()]);
        KategoriPengajuan::create(['nama_kategori' => 'Surat Keterangan Tidak Mampu (Sekolah)', 'created_at' => now(), 'updated_at' => now()]);
        KategoriPengajuan::create(['nama_kategori' => 'Surat Keterangan Status', 'created_at' => now(), 'updated_at' => now()]);
        KategoriPengajuan::create(['nama_kategori' => 'Surat Keterangan Belum Menikah', 'created_at' => now(), 'updated_at' => now()]);
        KategoriPengajuan::create(['nama_kategori' => 'Surat Keterangan Pekerjaan', 'created_at' => now(), 'updated_at' => now()]);
        KategoriPengajuan::create(['nama_kategori' => 'Surat Keterangan Penghasilan Orang Tua (Beasiswa)', 'created_at' => now(), 'updated_at' => now()]);
        KategoriPengajuan::create(['nama_kategori' => 'Surat Keterangan Usaha', 'created_at' => now(), 'updated_at' => now()]);
        KategoriPengajuan::create(['nama_kategori' => 'Surat Keterangan Penghasilan (Bantuan)', 'created_at' => now(), 'updated_at' => now()]);
    }
}
