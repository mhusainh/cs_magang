<?php

namespace App\Http\Requests\BiodataOrtu;

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
            'nama_ayah' => 'required|string|max:255',
            'nama_ibu' =>'required|string|max:255',
            'no_telp' => 'required|string|max:255',
            'pekerjaan_ayah_id' => 'required|integer',
            'pekerjaan_ibu_id' => 'required|integer',
            'penghasilan_ortu_id' =>'required|integer'
        ];
    }

    public function messages(): array
    {
        return [
            'nama_ayah.required' => 'Nama Ayah Wajib Diisi',
            'nama_ayah.string' => 'Nama Ayah Harus Berupa String',
            'nama_ayah.max' => 'Nama Ayah Maksimal 255 Karakter',
            'nama_ibu.required' => 'Nama Ibu Wajib Diisi',
            'nama_ibu.string' => 'Nama Ibu Harus Berupa String',
            'nama_ibu.max' => 'Nama Ibu Maksimal 255 Karakter',
            'no_telp.required' => 'No Telp Wajib Diisi',
            'no_telp.string' => 'No Telp Harus Berupa String',
            'no_telp.max' => 'No Telp Maksimal 255 Karakter',
            'pekerjaan_ayah_id.required' => 'Pekerjaan Ayah Wajib Diisi',
            'pekerjaan_ayah_id.integer' => 'Pekerjaan Ayah Harus Berupa Integer',
            'pekerjaan_ibu_id.required' => 'Pekerjaan Ibu Wajib Diisi',
            'pekerjaan_ibu_id.integer' => 'Pekerjaan Ibu Harus Berupa Integer',
            'penghasilan_ortu_id.required' => 'Penghasilan Ortu Wajib Diisi',
            'penghasilan_ortu_id.integer' => 'Penghasilan Ortu Harus Berupa Integer',
        ];
       
    }
}
