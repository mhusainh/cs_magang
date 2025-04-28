<?php

namespace App\Http\Requests\Media;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class CreatePengajuanBiayaRequest extends FormRequest
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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'jenjang_sekolah' => 'required|string|max:255',
            'jurusan' => 'required|string|max:255'
        ];
    }

    public function messages(): array
    {
        return [
            'image.required' => 'Gambar harus diisi',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'File harus berupa jpeg, png, jpg, gif, svg',
            'image.max' => 'File tidak boleh lebih dari 5 MB',
            'jenjang_sekolah.required' => 'jenjang_sekolah harus diisi',
            'jenjang_sekolah.string' => 'jenjang_sekolah harus berupa string',
            'jenjang_sekolah.max' => 'jenjang_sekolah tidak boleh lebih dari 255 karakter',
            'jurusan.required' => 'jurusan harus diisi',
            'jurusan.string' => 'jurusan harus berupa string',
            'jurusan.max' => 'jurusan tidak boleh lebih dari 255 karakter'
        ];
    }
}
