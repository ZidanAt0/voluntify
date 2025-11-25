<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $names = ['Relawan','Workshop','Seminar','Webinar','Kopdar','Donor Darah','Pelatihan'];
        foreach ($names as $n) {
            Category::firstOrCreate(
                ['slug' => Str::slug($n)],
                ['name' => $n]
            );
        }
    }
}
