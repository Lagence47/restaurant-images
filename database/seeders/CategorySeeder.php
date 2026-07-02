<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => '⭐ Populaires'],
            ['name' => '🆕 Nouveautés'],
            ['name' => '🍽️ Menus'],
            ['name' => '🥗 Entrées'],
            ['name' => '🍛 Plats'],
            ['name' => '🍔 Burgers & Sandwichs'],
            ['name' => '🍕 Pizzas'],
            ['name' => '🍗 Grillades'],
            ['name' => '🍟 Accompagnements'],
            ['name' => '🍰 Desserts'],
            ['name' => '🥤 Boissons'],
            ['name' => '🎁 Promotions'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['name' => $cat['name']]);
        }
    }
}
