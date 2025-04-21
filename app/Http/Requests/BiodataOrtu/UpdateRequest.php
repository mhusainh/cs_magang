<?php

namespace App\Http\Requests\BiodataOrtu;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'nama_ayah' =>'sometimes|string|max:255',
            'nama_ibu' =>'sometimes|string|max:255',
            'no_telp' =>'sometimes|string|max:255',
            'pekerjaan_ayah_id' =>'sometimes|integer',
            'pekerjaan_ibu_id' =>'sometimes|integer',
            'penghasilan_ortu_id' =>'sometimes|integer',
        ];
    }

    public function messages(): array
    {
        return [
            'nama_ayah.string' => 'Nama ayah harus berupa string',
            'nama_ayah.max' => 'Nama ayah maksimal 255 karakter',
            'nama_ibu.string' => 'Nama ibu harus berupa string',
            'nama_ibu.max' => 'Nama ibu maksimal 255 karakter',
            'no_telp.string' => 'No telp harus berupa string',
            'no_telp.max' => 'No telp maksimal 255 karakter',
            'pekerjaan_ayah_id.integer' => 'Pekerjaan ayah harus berupa integer',
            'pekerjaan_ibu_id.integer' => 'Pekerjaan ibu harus berupa integer',
            'penghasilan_ortu_id.integer' => 'Penghasilan ortu harus berupa integer',
        ];
    }
}
