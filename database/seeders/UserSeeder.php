<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            [
                'email' => 'admin@gmail.com',
            ],
            [
                'name' => 'admin',
                'password' => Hash::make('12345678'), // ganti setelah deploy
                'role' => 'admin',
                'number_phone' => '085647234364',
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );
    }
}
