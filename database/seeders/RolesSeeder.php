<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Seed the application's base roles and an initial super admin user.
     */
    public function run(): void
    {
        Role::firstOrCreate([
            'name' => User::ROLE_SUPER_ADMIN,
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => User::ROLE_GYM_ADMIN,
            'guard_name' => 'web',
        ]);

        if (! User::where('email', 'admin@example.com')->exists()) {
            User::registerWithRole([
                'name' => 'Super Admin',
                'email' => 'admin@example.com',
                'password' => 'password123',
            ], User::ROLE_SUPER_ADMIN);
        }
    }
}
