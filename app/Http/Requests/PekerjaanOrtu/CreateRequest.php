<?php

namespace App\Http\Requests\PekerjaanOrtu;

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
            'nama_pekerjaan' => 'required|string|max:255|unique:pekerjaan_ortus,nama_pekerjaan'
        ];
    }
    public function messages()
    {
        return [
            'nama_pekerjaan.required' => 'Nama pekerjaan harus diisi',
            'nama_pekerjaan.string' => 'Nama pekerjaan harus berupa string',
            'nama_pekerjaan.max' => 'Nama pekerjaan maksimal 255 karakter',
            'nama_pekerjaan.unique' => 'Nama pekerjaan sudah ada' 
        ];
    }
} 