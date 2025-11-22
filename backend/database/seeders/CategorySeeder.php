<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $default = [
            ['user_id' => null, 'name' => 'Salário', 'icon' => '💼', 'color' => '#10b981', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => null, 'name' => 'Freelancer', 'icon' => '💻', 'color' => '#06b6d4', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => null, 'name' => 'Investimentos', 'icon' => '📈', 'color' => '#8b5cf6', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => null, 'name' => 'Vendas', 'icon' => '🛒', 'color' => '#f59e0b', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => null, 'name' => 'Outros', 'icon' => '💰', 'color' => '#64748b', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('categories')->insert($default);
    }
}
