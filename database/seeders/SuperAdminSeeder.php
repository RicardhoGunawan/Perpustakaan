<?php

// database/seeders/SuperAdminSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::firstOrCreate(['name' => 'super_admin']);

        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'), // Ganti sesuai keinginan
            ]
        );

        $user->assignRole($role);
    }
}


