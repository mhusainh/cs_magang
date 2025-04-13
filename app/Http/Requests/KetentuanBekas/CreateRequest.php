<?php

namespace App\Http\Requests\KetentuanBekas;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    use FormRequestTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:255',
            'jenjang_sekolah' =>'required|string|max:255',
            'is_required' =>'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'nama.required' => 'Nama tidak boleh kosong',
            'nama.string' => 'Nama harus berupa string',
            'nama.max' => 'Nama maksimal 255 karakter',
            'jenjang_sekolah.required' => 'Jenjang sekolah tidak boleh kosong',
            'jenjang_sekolah.string' => 'Jenjang sekolah harus berupa string',
            'jenjang_sekolah.max' => 'Jenjang sekolah maksimal 255 karakter',
            'is_required.required' => 'Is required tidak boleh kosong',
            'is_required.boolean' => 'Is required harus berupa boolean',
        ];
    }
}
