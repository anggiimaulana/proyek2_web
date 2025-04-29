<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengajuanSktmBeasiswaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hubungan' => $this->hubunganPengaju->jenis_hubungan ?? null,
            'nama_anak' => $this->nama,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'suku' => $this->suku,
            'jk' => $this->jenisKelaminPengaju->jenis_kelamin ?? null,
            'agama' => $this->agamaPengaju->nama_agama ?? null,
            'pekerjaan_anak' => $this->pekerjaanAnakPengaju->nama_pekerjaan ?? null,
            'nama' => $this->nama,
            'nama_ibu' => $this->nama_ibu,
            'pekerjaan_ortu' => $this->pekerjaanOrtuPengaju->nama_pekerjaan ?? null,
            'file_kk' => $this->file_kk,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
