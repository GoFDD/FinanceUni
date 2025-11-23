<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class ExpenseBaseCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categoriasBase = [
            ['name' => 'Moradia', 'icon' => '🏠', 'type' => 'expense'],
            ['name' => 'Alimentação', 'icon' => '🍽️', 'type' => 'expense'],
            ['name' => 'Transporte', 'icon' => '🚗', 'type' => 'expense'],
            ['name' => 'Saúde', 'icon' => '🩺', 'type' => 'expense'],
            ['name' => 'Despesas Financeiras', 'icon' => '💳', 'type' => 'expense'],
            ['name' => 'Lazer', 'icon' => '🎉', 'type' => 'expense'],
        ];

        foreach ($categoriasBase as $cat) {
            Category::firstOrCreate(
                ['name' => $cat['name'], 'user_id' => null],
                ['icon' => $cat['icon'], 'type' => $cat['type']]
            );
        }
    }
}
