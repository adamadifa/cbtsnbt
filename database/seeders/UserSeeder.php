<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin CBT',
            'email' => 'admin@cbt.test',
            'password' => Hash::make('password'),
        ]);

        $admin->assignRole('admin');

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super@cbt.test',
            'password' => Hash::make('password'),
        ]);

        $superAdmin->assignRole('super_admin');
    }
}
