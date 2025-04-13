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
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'ketentuan_berkas_id' => 'required|exists:ketentuan_berkas,id'
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
            'file.required' => 'File berkas harus diupload',
            'file.file' => 'Upload harus berupa file',
            'file.mimes' => 'Format file harus pdf, jpg, jpeg, atau png',
            'file.max' => 'Ukuran file maksimal 2MB',
            'ketentuan_berkas_id.required' => 'ID ketentuan berkas harus diisi',
            'ketentuan_berkas_id.exists' => 'ID ketentuan berkas tidak valid'
        ];
    }
}