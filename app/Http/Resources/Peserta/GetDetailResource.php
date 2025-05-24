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
            'nis' => $this->nis,
            'tempat_lahir' => $this->tempat_lahir,
            'tanggal_lahir' => $this->tanggal_lahir,
            'alamat' => $this->alamat,
            'asal_sekolah' => $this->asal_sekolah,
            'jurusan1' => $this->jurusan1 ? [
                'id' => $this->jurusan1->id,
                'jurusan' => $this->jurusan1->jurusan,
                'jenjang_sekolah' => $this->jurusan1->jenjang_sekolah,
            ] : null,
            'pengajuan_biaya' => $this->pengajuan_biaya,
            'wakaf' => $this->wakaf,
            'spp' => $this->spp,
            'book_vee' => $this->book_vee,
            'status' => $this->status,
            'biodata_ortu' => $this->biodataOrtu ? [
                'id' => $this->biodataOrtu->id,
                'nama_ayah' => $this->biodataOrtu->nama_ayah,
                'nama_ibu' => $this->biodataOrtu->nama_ibu,
                'no_telp' => $this->biodataOrtu->no_telp,
                'pekerjaan_ayah' => $this->biodataOrtu->pekerjaanAyah ? [
                    'id' => $this->biodataOrtu->pekerjaanAyah->id,
                    'pekerjaan' => $this->biodataOrtu->pekerjaanAyah->nama_pekerjaan
                ] : null,
                'pekerjaan_ibu' => $this->biodataOrtu->pekerjaanIbu ? [
                    'id' => $this->biodataOrtu->pekerjaanIbu->id,
                    'pekerjaan' => $this->biodataOrtu->pekerjaanIbu->nama_pekerjaan
                ] : null,
                'penghasilan_ortu' => $this->biodataOrtu->penghasilanOrtu ? [
                    'id' => $this->biodataOrtu->penghasilanOrtu->id,
                    'penghasilan' => $this->biodataOrtu->penghasilanOrtu->penghasilan_ortu
                ] : null
            ] : null,
            'berkas' => $this->berkas ? $this->berkas->map(function ($berkas) {
                return [
                    'id' => $berkas->id,
                    'nama_file' => $berkas->nama_file,
                    'url_file' => $berkas->url_file,
                    'public_id' => $berkas->public_id,
                    'ketentuan_berkas_id' => $berkas->ketentuan_berkas_id,
                ];
            }) : null,
            'angkatan' => $this->angkatan,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
