<?php

namespace App\Http\Requests\KetentuanBerkas;

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
            'nama' => 'sometimes|string',
            'jenjang_sekolah' =>'sometimes|string',
            'is_required' =>'sometimes|boolean'
        ];
    }

    public function messages()
    {
        return [
            'nama.string' => 'Nama harus berupa string',
            'jenjang_sekolah.string' => 'Jenjang sekolah harus berupa string',
            'is_required.boolean' => 'Is required harus berupa boolean'
        ];
    }
}
