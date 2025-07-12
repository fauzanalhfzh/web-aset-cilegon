<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'sign' => null,
            'foto' => null,
            'role' => 'Admin',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Lurah',
            'email' => 'lurah@example.com',
            'password' => Hash::make('password123'),
            'sign' => null,
            'foto' => null,
            'role' => 'Lurah',
            'is_active' => true,
        ]);
    }
}