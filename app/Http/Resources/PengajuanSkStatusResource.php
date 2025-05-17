<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengajuanSkStatusResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hubungan' => $this->hubunganPengaju->jenis_hubungan ?? null,
            'nama' => $this->nama,
            'kk_id' => $this->idKkPengaju->nomor_kk ?? null,
            'nik_id' => $this->idNikPengaju->nomor_nik ?? null,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jk' => $this->jenisKelaminPengaju->jenis_kelamin ?? null,
            'agama' => $this->agamaPengaju->nama_agama ?? null,
            'status_perkawinan' => $this->statusPerkawinanPengaju->status_perkawinan ?? null,
            'pekerjaan' => $this->pekerjaanPengaju->nama_pekerjaan ?? null,
            'alamat' => $this->alamat,
            'file_kk' => $this->file_kk,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
