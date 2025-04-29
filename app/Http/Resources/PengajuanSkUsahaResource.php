<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengajuanSkUsahaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hubungan' => $this->hubunganPengaju->jenis_hubungan ?? null,
            'nama' => $this->nama,
            'nik' => $this->nik,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jk' => $this->jenisKelaminPengaju->jenis_kelamin ?? null,
            'status_perkawinan' => $this->statusPerkawinanPengaju->status_perkawinan ?? null,
            'pekerjaan' => $this->pekerjaanPengaju->nama_pekerjaan ?? null,
            'alamat' => $this->alamat,
            'nama_usaha' => $this->nama_usaha,
            'file_ktp' => $this->file_ktp,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
