<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::firstOrCreate(
            ['email' => 'neupanesarthak3000@gmail.com'],
            [
                'name'              => 'Sarthak Neupane',
                'password'          => Hash::make('passw0rd'),
                'is_admin'          => true,
                'email_verified_at' => now(),
            ]
        );

        
    }
}