<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string',
            'no_telp' => 'required|string|max:15',
            'jenjang_sekolah' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama harus diisi',
            'nama.max' => 'Nama maksimal 255 karakter',
            'jenis_kelamin.required' => 'Jenis kelamin harus diisi',
            'jenis_kelamin.in' => 'Jenis kelamin tidak valid',
            'no_telp.required' => 'Nomor telepon harus diisi',
            'no_telp.max' => 'Nomor telepon maksimal 15 karakter',
            'jenjang_sekolah.required' => 'Jenjang sekolah harus diisi',
            'jenjang_sekolah.in' => 'Jenjang sekolah tidak valid',

        ];
    }
} 