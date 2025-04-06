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
    public function messages()
    {
        return [
            'nama_pekerjaan.unique' => 'Pekerjaan sudah ada',
            'nama_pekerjaan.required' => 'Pekerjaan tidak boleh kosong',
            'nama_pekerjaan.string' => 'Pekerjaan harus berupa string',
            'nama_pekerjaan.max' => 'Pekerjaan maksimal 255 karakter'
        ];
    }
}
