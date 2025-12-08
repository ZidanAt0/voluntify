<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['admin', 'organizer', 'user'] as $name) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $admin = User::firstOrCreate(
            ['email' => 'admin@voluntify.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(), // Admin tidak perlu verifikasi email
            ]
        );
        $admin->syncRoles('admin');

        $organizer = User::firstOrCreate(
            ['email' => 'organizer@voluntify.com'],
            [
                'name' => 'Organizer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(), // Organizer di seeder langsung verified
                'organizer_verified_at' => now(), // Organizer di seeder langsung verified oleh "sistem"
            ]
        );
        $organizer->syncRoles('organizer');

        $member = User::firstOrCreate(
            ['email' => 'user@voluntify.com'],
            [
                'name' => 'User',
                'password' => Hash::make('password'),
                'email_verified_at' => now(), // User di seeder langsung verified untuk testing
            ]
        );
        $member->syncRoles('user');
    }
}
