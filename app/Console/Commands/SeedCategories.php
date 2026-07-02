<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class SeedCategories extends Command
{
    protected $signature = 'categories:seed';
    protected $description = 'Créer les catégories par défaut du restaurant';

    public function handle(): int
    {
        $categories = [
            '⭐ Populaires',
            '🆕 Nouveautés',
            '🍽️ Menus',
            '🥗 Entrées',
            '🍛 Plats',
            '🍔 Burgers & Sandwichs',
            '🍕 Pizzas',
            '🍗 Grillades',
            '🍟 Accompagnements',
            '🍰 Desserts',
            '🥤 Boissons',
            '🎁 Promotions',
        ];

        $created = 0;
        foreach ($categories as $name) {
            if (Category::firstOrCreate(['name' => $name])->wasRecentlyCreated) {
                $created++;
            }
        }

        $this->info("✅ {$created} catégorie(s) créée(s), " . count($categories) . " total");
        return 0;
    }
}
