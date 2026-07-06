<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $workshopId = auth()->user()->workshop_id;

        return [
            'workshop_name' => 'required|string|max:255',
            'phone_number'  => 'required|string|max:20',
            'address'       => 'nullable|string|max:500',
            'email'         => [
                'required',
                'email',
                'max:255',
                Rule::unique('workshops', 'email')->ignore($workshopId, 'workshop_id'),
            ],
            'password'      => 'nullable|string|min:8|confirmed',
            'logo'          => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique'           => 'Email ini sudah digunakan oleh bengkel lain.',
            'password.confirmed'     => 'Konfirmasi password baru tidak cocok.',
            'logo.max'               => 'Ukuran logo maksimal 2MB.',
            'logo.image'             => 'File harus berupa gambar.',
            'logo.mimes'             => 'Format logo harus JPEG, PNG, atau JPG.',
            'workshop_name.required' => 'Nama bengkel wajib diisi.',
            'phone_number.required'  => 'Nomor telepon wajib diisi.',
        ];
    }
}
