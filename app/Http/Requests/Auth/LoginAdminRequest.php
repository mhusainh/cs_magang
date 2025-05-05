<?php

namespace App\Http\Requests\Auth;

use App\Traits\FormRequestTrait;
use Illuminate\Foundation\Http\FormRequest;

class LoginAdminRequest extends FormRequest
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
            'no_telp' => 'required',
            'password' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
            'no_telp.required' => 'Nomor telepon harus diisi',
            'password.required' => 'Password harus diisi',
        ];
    }
}
