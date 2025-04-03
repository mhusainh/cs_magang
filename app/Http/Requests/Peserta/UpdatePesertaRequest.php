<?php

namespace App\Http\Requests\Peserta;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormRequestTrait;

class UpdatePesertaRequest extends FormRequest
{
    use FormRequestTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'no_telp' => 'required|string|max:15',
            'jenis_kelamin' => 'required|string|in:Laki-laki,Perempuan',
            'jenjang_sekolah' => 'required|string|in:SD,SMP,SMA',
            'nisn' => 'required|string|max:10',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'jurusan1_id' => 'nullable|integer|exists:jurusan,id',
            'jurusan2_id' => 'nullable|integer|exists:jurusan,id'
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama harus diisi',
            'nama.string' => 'Nama harus berupa teks',
            'nama.max' => 'Nama maksimal 255 karakter',
            'no_telp.required' => 'Nomor telepon harus diisi',
            'no_telp.string' => 'Nomor telepon harus berupa teks',
            'no_telp.max' => 'Nomor telepon maksimal 15 karakter',
            'jenis_kelamin.required' => 'Jenis kelamin harus diisi',
            'jenis_kelamin.string' => 'Jenis kelamin harus berupa teks',
            'jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki atau Perempuan',
            'jenjang_sekolah.required' => 'Jenjang sekolah harus diisi',
            'jenjang_sekolah.string' => 'Jenjang sekolah harus berupa teks',
            'jenjang_sekolah.in' => 'Jenjang sekolah harus SD, SMP, atau SMA',
            'nisn.required' => 'NISN harus diisi',
            'nisn.string' => 'NISN harus berupa teks',
            'nisn.max' => 'NISN maksimal 10 karakter',
            'tempat_lahir.required' => 'Tempat lahir harus diisi',
            'tempat_lahir.string' => 'Tempat lahir harus berupa teks',
            'tempat_lahir.max' => 'Tempat lahir maksimal 255 karakter',
            'tanggal_lahir.required' => 'Tanggal lahir harus diisi',
            'tanggal_lahir.date' => 'Tanggal lahir harus berupa tanggal',
            'alamat.required' => 'Alamat harus diisi',
            'alamat.string' => 'Alamat harus berupa teks',
            'alamat.max' => 'Alamat maksimal 255 karakter',
            'jurusan1_id.integer' => 'ID jurusan 1 harus berupa angka',
            'jurusan1_id.exists' => 'jurusan tidak ditemukan',
            'jurusan2_id.integer' => 'ID jurusan 2 harus berupa angka',
            'jurusan2_id.exists' => 'jurusan tidak ditemukan'
        ];
    }
}
