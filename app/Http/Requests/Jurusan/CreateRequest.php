<?php

namespace App\Http\Requests\Jurusan;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormRequestTrait;

class CreateRequest extends FormRequest
{
    use FormRequestTrait;
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jurusan' => 'required|string|max:255|unique:jurusan,jurusan',
            'jenjang_sekolah' => 'nullable|string|max:50'
        ];
    }

    public function messages(): array
    {
        return [
            'jurusan.required' => 'Nama jurusan wajib diisi',
            'jurusan.max' => 'Nama jurusan maksimal 255 karakter',
            'jurusan.unique' => 'Jurusan sudah terdaftar',
            'jenjang_sekolah.max' => 'Jenjang sekolah maksimal 50 karakter',
            'jenjang_sekolah.string' => 'Jenjang sekolah harus berupa teks'
        ];
    }
} 