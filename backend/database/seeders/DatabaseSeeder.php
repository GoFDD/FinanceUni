<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AchievementsSeeder::class,
        ]);
        $this->call([
            CategorySeeder::class,
        ]);
        $this->call([
            ExpenseBaseCategoriesSeeder::class,
        ]);
    }
}
