<?php

namespace App\Http\Requests\ServicePart;

use Illuminate\Foundation\Http\FormRequest;

class StoreServicePartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transaction_id' => 'required|exists:transactions,transaction_id',
            'sparepart_id'   => 'required|exists:spareparts,sparepart_id',
            'qty'            => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'transaction_id.required' => 'Transaksi tidak valid.',
            'transaction_id.exists'   => 'Transaksi tidak ditemukan.',
            'sparepart_id.required'   => 'Sparepart harus dipilih.',
            'sparepart_id.exists'     => 'Sparepart tidak ditemukan.',
            'qty.required'            => 'Jumlah wajib diisi.',
            'qty.integer'             => 'Jumlah harus angka bulat.',
            'qty.min'                 => 'Jumlah minimal 1.',
        ];
    }
}
