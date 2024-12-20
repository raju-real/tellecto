<?php

namespace Database\Seeders;

use App\Models\RolePermission\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            'name' => 'Super Admin',
            'type' => 'SUPER',
            'status' => 1
        ]);
        Role::insert([
            'name' => 'Admin',
            'type' => 'ADMIN',
            'status' => 1
        ]);
        Role::insert([
            'name' => 'Business',
            'type' => 'BUSINESS',
            'status' => 1
        ]);

        User::insert([
            'name' => 'Super Admin',
            'role_id' => 1,
            'username' => 'super-admin',
            'email' => 'su@tellecto.se',
            'password' => Hash::make('123456'),
            'is_active' => true,
            'created_at' => now()
        ]);
        User::insert([
            'name' => 'Super Admin',
            'role_id' => 3,
            'username' => 'business',
            'email' => 'bu@tellecto.se',
            'password' => Hash::make('123456'),
            'is_active' => true,
            'created_at' => now()
        ]);

    }
}
