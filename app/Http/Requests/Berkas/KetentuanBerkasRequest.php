<?php

namespace App\Http\Requests\Berkas;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormRequestTrait;

class KetentuanBerkasRequest extends FormRequest
{
    use FormRequestTrait;
    
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
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
            'jenjang_sekolah' => 'required|string|max:255',
            'is_required' => 'required|boolean'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama berkas harus diisi',
            'nama.string' => 'Nama berkas harus berupa teks',
            'nama.max' => 'Nama berkas maksimal 255 karakter',
            'jenjang_sekolah.required' => 'Jenjang sekolah harus diisi',
            'jenjang_sekolah.string' => 'Jenjang sekolah harus berupa teks',
            'jenjang_sekolah.max' => 'Jenjang sekolah maksimal 255 karakter',
            'is_required.required' => 'Status wajib harus diisi',
            'is_required.boolean' => 'Status wajib harus berupa boolean'
        ];
    }
}