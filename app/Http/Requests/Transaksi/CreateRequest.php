<?php

namespace App\Http\Requests\Transaksi;

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
            'tagihan_id' => 'required|exists:tagihans,id',
            'jumlah' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|in:transfer,bank,ewallet',
            'status' => 'required|string|in:pending,success,failed',
            'waktu_transaksi' => 'required|date'
        ];
    }

    public function messages(): array
    {
        return [
            'tagihan_id.required' => 'ID tagihan wajib diisi',
            'tagihan_id.exists' => 'Tagihan tidak valid',
            'jumlah.required' => 'Jumlah pembayaran wajib diisi',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 0',
            'metode_pembayaran.required' => 'Metode pembayaran wajib diisi',
            'metode_pembayaran.in' => 'Metode pembayaran tidak valid',
            'status.required' => 'Status transaksi wajib diisi',
            'status.in' => 'Status harus berupa pending, success, atau failed',
            'waktu_transaksi.required' => 'Waktu transaksi wajib diisi',
            'waktu_transaksi.date' => 'Format waktu transaksi tidak valid'
        ];
    }
} 