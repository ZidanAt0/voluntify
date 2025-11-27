<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\User;
use App\Models\Category;
use Illuminate\Support\Facades\Hash;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::firstOrCreate(
            ['email' => 'organizer@local.test'],
            ['name' => 'Organizer', 'password' => Hash::make('password')]
        );
        if (method_exists($organizer, 'assignRole')) {
            $organizer->syncRoles('organizer');
        }

        $categories = Category::all();
        if ($categories->isEmpty()) {
            $this->call(CategorySeeder::class);
        }
        $categories = Category::all();

        Event::factory()
            ->count(20)
            ->state(fn () => [
                'organizer_id' => $organizer->id,
                'category_id'  => $categories->random()->id,
            ])
            ->create();
    }
}
