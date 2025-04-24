<?php

namespace App\Http\Resources\Berkas;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetAllBerkasResource extends JsonResource
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
            'nama_file' => $this->nama_file,
            'url_file' => $this->url_file,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'ketentuan_berkas' => $this->ketentuanBerkas ? [
                'id' => $this->ketentuanBerkas->id,
                'nama' => $this->ketentuanBerkas->nama,
                'jenjang_sekolah' => $this->ketentuanBerkas->jenjang_sekolah,
                'is_required' => $this->ketentuanBerkas->is_required,
            ] : null,
            'peserta' => $this->peserta ? [
                'id' => $this->peserta->id,
                'nama' => $this->peserta->nama,
                'nisn' => $this->peserta->nisn,
            ] : null,
        ];
    }
}
