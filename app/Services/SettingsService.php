<?php

namespace App\Services;

use App\Models\Workshop;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SettingsService
{
    public function updateProfile(Workshop $workshop, array $data): void
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if (!empty($data['logo']) && is_object($data['logo'])) {
            if ($workshop->logo && Storage::disk('public')->exists($workshop->logo)) {
                Storage::disk('public')->delete($workshop->logo);
            }
            $data['logo'] = $data['logo']->store('workshop-logos', 'public');
        }

        $workshop->update($data);
    }
}
