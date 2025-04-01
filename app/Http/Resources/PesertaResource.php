<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PesertaResource extends JsonResource
{
    public function toArray($request)
    {
        if ($this->resource->wasRecentlyCreated) {
            return $this->registrationResponse();
        }
        return $this->defaultResponse();
    }

    protected function defaultResponse()
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'no_telp' => $this->no_telp,
            'jenis_kelamin' => $this->jenis_kelamin,
            'jenjang_sekolah' => $this->jenjang_sekolah,
            'created_at' => $this->created_at->format('Y-m-d')
        ];
    }

    protected function registrationResponse()
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'no_telp' => $this->no_telp,
            'token' => $this->user->createToken('auth_token')->plainTextToken
        ];
    }
}
