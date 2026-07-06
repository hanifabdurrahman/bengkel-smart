<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'workshop_name' => 'required|string|max:255',
            'email'         => 'required|string|email|max:255|unique:workshops,email',
            'phone_number'  => 'required|string|max:20',
            'address'       => 'required|string',
            'password'      => 'required|string|min:8|confirmed',
        ];
    }

    public function messages(): array
    {
        return [
            'workshop_name.required' => 'Nama bengkel wajib diisi.',
            'email.required'         => 'Email wajib diisi.',
            'email.email'            => 'Format email tidak valid.',
            'email.unique'           => 'Email ini sudah terdaftar.',
            'phone_number.required'  => 'Nomor telepon wajib diisi.',
            'address.required'       => 'Alamat wajib diisi.',
            'password.required'      => 'Password wajib diisi.',
            'password.min'           => 'Password minimal 8 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
        ];
    }
}
