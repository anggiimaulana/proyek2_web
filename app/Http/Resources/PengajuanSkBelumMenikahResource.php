<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PengajuanSkBelumMenikahResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kk_id' => $this->idKkPengaju->nomor_kk ?? null,
            'nik_id' => $this->idNikPengaju->nomor_nik ?? null,
            'nama' => $this->nama,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'jenis_kelamin' => $this->jenisKelaminPengaju->jenis_kelamin ?? null,
            'agama' => $this->agamaPengaju->nama_agama ?? null,
            'pekerjaan' => $this->pekerjaanPengaju->nama_pekerjaan ?? null,
            'status_perkawinan' => $this->statusPerkawinanPengaju->status_perkawinan ?? null,
            'hubungan' => $this->hubunganPengaju->jenis_hubungan ?? null,
            'file_kk' => $this->file_kk,
            'alamat' => $this->alamat,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
