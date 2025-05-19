<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NikResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nomor_nik' => $this->nomor_nik,
            'kk_id' => $this->kartuKeluarga->id ?? null,
            'name' => $this->name,
            'jk' => $this->clientJenisKelamin->jenis_kelamin ?? null,
            'hubungan' => $this->hubunganClient->jenis_hubungan ?? null,
            'agama' => $this->clientAgama->nama_agama ?? null,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'pekerjaan' => $this->clientPekerjaan->nama_pekerjaan ?? null,
            'pendidikan' => $this->clientPendidikan->jenis_pendidikan ?? null,
            'status' => $this->clientStatusPerkawinan->status_perkawinan ?? null,
            'alamat' => $this->alamat,
        ];
    }
}
