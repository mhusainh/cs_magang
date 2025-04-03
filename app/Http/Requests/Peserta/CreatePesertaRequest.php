<?php

namespace App\Http\Requests\Peserta;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormRequestTrait;

class CreatePesertaRequest extends FormRequest
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
            'jenjang_sekolah' => 'required|string|in:SD,SMP,SMA'
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
            'jenjang_sekolah.in' => 'Jenjang sekolah harus SD, SMP, atau SMA'
        ];
    }
}
