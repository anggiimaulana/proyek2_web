<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengajuanSktmSekolahResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hubungan' => $this->hubunganPengaju->jenis_hubungan ?? null,
            'nama' => $this->nama,
            'tempat_lahir_ortu' => $this->tempat_lahir_ortu,
            'tanggal_lahir_ortu' => $this->tanggal_lahir_ortu,
            'pekerjaan' => $this->pekerjaanPengaju->nama_pekerjaan ?? null,
            'alamat' => $this->alamat,
            'nama_anak' => $this->nama_anak,
            'tempat_lahir' => $this->tempat_lahir,            
            'tanggal_lahir' => $this->tanggal_lahir,
            'jk' => $this->jenisKelaminPengaju->jenis_kelamin ?? null,
            'agama' => $this->agamaPengaju->nama_agama ?? null,
            'asal_sekolah' => $this->asal_sekolah,
            'kelas' => $this->kelas,
            'file_kk' => $this->file_kk,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
