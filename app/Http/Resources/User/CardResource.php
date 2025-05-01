<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'no_telp' => $this->no_telp,
            'peserta' => $this->peserta ? [
                'id' => $this->peserta->id,
                'nis' => $this->peserta->nis,
                'nama' => $this->peserta->nama,
                'nisn' => $this->peserta->nisn,
                'jenis_kelamin' => $this->peserta->jenis_kelamin,
                'jenjang_sekolah' => $this->peserta->jenjang_sekolah,
                'status' => $this->peserta->status,
                'jurusan1' => $this->peserta->jurusan1 ? [
                    'id' => $this->peserta->jurusan1->id,
                    'jurusan' => $this->peserta->jurusan1->jurusan,
                    'jenjang_sekolah' => $this->peserta->jurusan1->jenjang_sekolah,
                ] : null,
            ] : null,
            'progressUser' => $this->progressUser ? [
                'id' => $this->progressUser->id,
                'progress' => $this->progressUser->progress,
            ] : null,
            'pesan' => $this->pesan()->where('is_read', false)->count(),
        ];
    }
}
