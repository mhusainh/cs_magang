<?php

namespace App\Http\Requests\Peserta;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormRequestTrait;

class InputFormPesertaRequest extends FormRequest
{
    use FormRequestTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nisn' => 'required|string|max:10',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'alamat' => 'required|string|max:255',
            'jurusan1_id' => 'nullable|integer|exists:jurusan,id',
        ];
    }

    public function messages(): array
    {
        return [
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
