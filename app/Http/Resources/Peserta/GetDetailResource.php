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
            'jurusan1' => $this->jurusan1 ? [
                'id' => $this->jurusan1->id,
                'jurusan' => $this->jurusan1->jurusan,
                'jenjang_sekolah' => $this->jurusan1->jenjang_sekolah,
            ] : null,
            'jurusan2' => $this->jurusan2 ? [
                'id' => $this->jurusan2->id,
                'jurusan' => $this->jurusan2->jurusan,
                'jenjang_sekolah' => $this->jurusan2->jenjang_sekolah,
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
