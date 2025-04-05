<?php

namespace App\Http\Requests\PekerjaanOrtu;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
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
} 