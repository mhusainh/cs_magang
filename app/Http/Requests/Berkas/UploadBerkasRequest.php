<?php

namespace App\Http\Requests\Berkas;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\FormRequestTrait;

class UploadBerkasRequest extends FormRequest
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
            'files' => 'required|array',
            'files.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ketentuan_berkas_ids' => 'required|array',
            'ketentuan_berkas_ids.*' => 'required|exists:ketentuan_berkas,id'
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
            'files.required' => 'File berkas harus diupload',
            'files.array' => 'File berkas harus berupa array',
            'files.*.required' => 'Setiap file berkas harus diupload',
            'files.*.file' => 'Upload harus berupa file',
            'files.*.mimes' => 'Format file harus pdf, jpg, jpeg, atau png',
            'files.*.max' => 'Ukuran file maksimal 2MB',
            'ketentuan_berkas_ids.required' => 'ID ketentuan berkas harus diisi',
            'ketentuan_berkas_ids.array' => 'ID ketentuan berkas harus berupa array',
            'ketentuan_berkas_ids.*.required' => 'Setiap ID ketentuan berkas harus diisi',
            'ketentuan_berkas_ids.*.exists' => 'ID ketentuan berkas tidak valid'
        ];
    }
}
