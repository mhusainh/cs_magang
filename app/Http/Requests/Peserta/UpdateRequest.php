<?php

namespace App\Http\Requests\Peserta;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama peserta wajib diisi',
            'nama.max' => 'Nama peserta maksimal 255 karakter',
            'nisn.unique' => 'NISN sudah terdaftar',
            'jurusan_id.exists' => 'Jurusan tidak valid',
            'pekerjaan_ortu_id.exists' => 'Pekerjaan orang tua tidak valid'
        ];
    }

    public function authorize()
    {
        // Implementasi authorization logic
    }

    public function rules()
    {
        // Implementasi rules logic
    }
} 