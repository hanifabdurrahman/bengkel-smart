<?php

namespace App\Http\Requests\Sparepart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSparepartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sparepart_name' => 'required|string|max:255',
            'sparepart_code' => [
                'nullable',
                'string',
                'max:100',
                Rule::unique('spareparts')->where(function ($query) {
                    return $query->where('workshop_id', auth()->user()->workshop_id);
                })
            ],
            'stock_quantity' => 'required|integer|min:0',
            'buying_price'   => 'required|numeric|min:0',
            'selling_price'  => 'required|numeric|min:0',
            'rack_location'  => 'nullable|string|max:100',
            'entry_date'     => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'sparepart_name.required' => 'Nama sparepart wajib diisi.',
            'sparepart_name.max'      => 'Nama sparepart terlalu panjang (maksimal 255 karakter).',
            'sparepart_code.unique'   => 'Kode sparepart ini sudah terdaftar di sistem stok Anda.',
            'stock_quantity.required' => 'Stok awal wajib diisi.',
            'stock_quantity.integer'  => 'Jumlah stok harus berupa angka bulat.',
            'stock_quantity.min'      => 'Stok tidak boleh kurang dari 0.',
            'buying_price.required'   => 'Harga beli (modal) wajib diisi.',
            'buying_price.numeric'    => 'Harga beli harus berupa angka.',
            'buying_price.min'        => 'Harga beli tidak boleh negatif.',
            'selling_price.required'  => 'Harga jual wajib diisi.',
            'selling_price.numeric'   => 'Harga jual harus berupa angka.',
            'selling_price.min'       => 'Harga jual tidak boleh negatif.',
            'entry_date.required'     => 'Tanggal masuk barang wajib dipilih.',
            'entry_date.date'         => 'Format tanggal tidak valid.',
        ];
    }
}
