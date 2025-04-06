<?php

namespace App\Http\Requests\PekerjaanOrtu;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nama_pekerjaan' => 'sometimes|string|max:255|unique:pekerjaan_ortus,nama_pekerjaan,' . $this->route('id')
        ];
    }
    public function messages(): array
    {
        return [
            'nama_pekerjaan.unique' => 'Pekerjaan sudah terdaftar',
            'nama_pekerjaan.max' => 'Nama pekerjaan maksimal 255 karakter',
            'nama_pekerjaan.string' => 'Nama pekerjaan harus berupa teks'
        ];
    }
}
