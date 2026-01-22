<?php

namespace Database\Seeders;

use App\Models\AdminSistem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdminSistem::create([
            'name' => 'Super Admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => Hash::make('adminsistem123'),
            'email_verified_at' => now(),
            'remember_token' => Str::random(60),
        ]);
    }
}