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
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );
        $admin->syncRoles('admin');

        $organizer = User::firstOrCreate(
            ['email' => 'organizer@voluntify.com'],
            ['name' => 'Organizer', 'password' => Hash::make('password')]
        );
        $organizer->syncRoles('organizer');

        $member = User::firstOrCreate(
            ['email' => 'user@voluntify.com'],
            ['name' => 'User', 'password' => Hash::make('password')]
        );
        $member->syncRoles('user');
    }
}
