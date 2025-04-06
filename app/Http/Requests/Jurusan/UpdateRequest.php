<?php

namespace App\Http\Requests\Jurusan;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormRequestTrait;

class UpdateRequest extends FormRequest
{
    use FormRequestTrait;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jurusan' => 'sometimes|string|max:255|unique:jurusan,jurusan,'.$this->route('id'),
            'jenjang_sekolah' => 'nullable|string|max:50'
        ];
    }

    public function messages(): array
    {
        return [
            'jurusan.unique' => 'Jurusan sudah terdaftar',
            'jurusan.max' => 'Nama jurusan maksimal 255 karakter',
            'jenjang_sekolah.max' => 'Jenjang sekolah maksimal 50 karakter',
            'jenjang_sekolah.string' => 'Jenjang sekolah harus berupa teks'
        ];
    }
} 