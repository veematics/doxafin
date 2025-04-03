<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Viktor Iwan',
            'email' => 'viktor.iwan@doxadigital.com',
            'password' => Hash::make('doxa3692'),
        ]);
    }
}