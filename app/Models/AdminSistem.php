<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AdminSistem extends Authenticatable implements FilamentUser
{
    use Notifiable;

    protected $table = 'admin_sistems';
    protected $primaryKey = 'admin_id';

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }
}
