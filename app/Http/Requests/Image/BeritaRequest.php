<?php

namespace App\Http\Requests\Image;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormRequestTrait;


class BeritaRequest extends FormRequest
{
    use FormRequestTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Changed to true to allow the request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'urutan' => 'required|integer',
            'jenjang_sekolah' =>'required|string|max:5',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'image.required' => 'Gambar wajib diunggah',
            'image.image' => 'File yang diunggah harus berupa gambar',
            'image.mimes' => 'Format gambar tidak didukung. Hanya file JPEG, PNG, JPG, dan GIF yang diizinkan.',
            'image.max' => 'Ukuran gambar tidak boleh melebihi 5 MB.',
            'urutan.required' => 'Urutan wajib diisi',
            'urutan.integer' => 'Urutan harus berupa angka',
            'jenjang_sekolah.required' => 'Jenjang sekolah wajib diisi',
            'jenjang_sekolah.string' => 'Jenjang sekolah harus berupa string',
            'jenjang_sekolah.max' => 'Jenjang sekolah tidak boleh melebihi 5 karakter'
        ];
    }
}
