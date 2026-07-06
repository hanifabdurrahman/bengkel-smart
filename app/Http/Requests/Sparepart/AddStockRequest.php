<?php

namespace App\Http\Requests\Sparepart;

use Illuminate\Foundation\Http\FormRequest;

class AddStockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'qty_masuk'       => 'required|integer|min:1',
            'harga_beli_baru' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'qty_masuk.required'       => 'Jumlah stok masuk wajib diisi.',
            'qty_masuk.integer'        => 'Jumlah stok harus berupa angka bulat.',
            'qty_masuk.min'            => 'Jumlah stok minimal 1.',
            'harga_beli_baru.required' => 'Harga beli baru wajib diisi.',
            'harga_beli_baru.numeric'  => 'Harga beli harus berupa angka.',
            'harga_beli_baru.min'      => 'Harga beli tidak boleh negatif.',
        ];
    }
}
