<?php

namespace App\Http\Resources\Peserta;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'nama' => $this->nama,
            'no_telp' => $this->no_telp,
            'jenis_kelamin' => $this->jenis_kelamin,
            'jenjang_sekolah' => $this->jenjang_sekolah,
            'nisn' => $this->nisn,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'alamat' => $this->alamat,
            'jurusan1_id' => $this->jurusan1_id,
            'jurusan2_id' => $this->jurusan2_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
} 