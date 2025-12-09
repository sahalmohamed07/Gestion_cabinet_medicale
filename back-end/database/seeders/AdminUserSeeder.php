<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@alfirdaws.com'],
            [
                'nom'      => 'Admin',
                'prenom'   => 'Principal',
                'password' => Hash::make('passer'),
                'role'     => 'admin',
            ]
        );
    }
}
