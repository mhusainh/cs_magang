<?php

namespace App\Http\Requests\Tagihan;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'nama_tagihan' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,paid,expired',
            'va_number' => 'required|string|unique:tagihans,va_number',
            'transaction_qr_id' => 'required|string',
            'created_time' => 'required|date'
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'ID user wajib diisi',
            'user_id.exists' => 'User tidak valid',
            'nama_tagihan.required' => 'Nama tagihan wajib diisi',
            'nama_tagihan.max' => 'Nama tagihan maksimal 255 karakter',
            'total.required' => 'Total tagihan wajib diisi',
            'total.numeric' => 'Total harus berupa angka',
            'total.min' => 'Total tidak boleh kurang dari 0',
            'status.required' => 'Status wajib diisi',
            'status.in' => 'Status harus berupa pending, paid, atau expired',
            'va_number.required' => 'Nomor VA wajib diisi',
            'va_number.unique' => 'Nomor VA sudah digunakan',
            'transaction_qr_id.required' => 'ID transaksi QR wajib diisi',
            'created_time.required' => 'Waktu pembuatan wajib diisi',
            'created_time.date' => 'Format waktu tidak valid'
        ];
    }
} 