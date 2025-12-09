<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan organizer ada
        $organizer = User::firstOrCreate(
            ['email' => 'organizer@local.test'],
            [
                'name' => 'Organizer',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'organizer_verified_at' => now(),
            ]
        );
        if (method_exists($organizer, 'assignRole')) {
            $organizer->syncRoles('organizer');
        }

        // Pastikan kategori tersedia
        $this->call(CategorySeeder::class);
        $cat = fn($slug) => optional(Category::where('slug', $slug)->first())->id;

        Event::factory()
            ->count(20)
            ->state(fn() => [
                'organizer_id' => $organizer->id,
                'category_id'  => $categories->random()->id,
            ])
            ->create();
    }
}
