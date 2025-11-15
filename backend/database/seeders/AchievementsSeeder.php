<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AchievementsSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('achievements')->insert([

            // 🔹 COMMON
            [
                'title' => 'Primeiro Login',
                'description' => 'Acesse o aplicativo pela primeira vez.',
                'xp_reward' => 10,
                'rarity' => 'common',
                'icon' => 'first_login',
                'status' => 'locked',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Primeira Meta Criada',
                'description' => 'Crie sua primeira meta financeira.',
                'xp_reward' => 20,
                'rarity' => 'common',
                'icon' => 'first_goal',
                'status' => 'locked',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 🔹 RARE
            [
                'title' => 'Streak de 7 Dias',
                'description' => 'Acesse o site por 7 dias seguidos.',
                'xp_reward' => 50,
                'rarity' => 'rare',
                'icon' => 'streak_7',
                'status' => 'locked',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Meta Concluída',
                'description' => 'Conclua sua primeira meta.',
                'xp_reward' => 75,
                'rarity' => 'rare',
                'icon' => 'goal_complete',
                'status' => 'locked',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 🔹 EPIC
            [
                'title' => 'Streak de 30 Dias',
                'description' => 'Acesse o site por 30 dias seguidos.',
                'xp_reward' => 150,
                'rarity' => 'epic',
                'icon' => 'streak_30',
                'status' => 'locked',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Investidor Nível 1',
                'description' => 'Invista pela primeira vez.',
                'xp_reward' => 200,
                'rarity' => 'epic',
                'icon' => 'invest_1',
                'status' => 'locked',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // 🔹 LEGENDARY
            [
                'title' => 'Streak de 100 Dias',
                'description' => 'Acesse o site por 100 dias seguidos.',
                'xp_reward' => 500,
                'rarity' => 'legendary',
                'icon' => 'streak_100',
                'status' => 'locked',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'Mestre das Metas',
                'description' => 'Complete 10 metas financeiras.',
                'xp_reward' => 1000,
                'rarity' => 'legendary',
                'icon' => 'goal_master',
                'status' => 'locked',
                'created_at' => $now,
                'updated_at' => $now,
            ],

        ]);
    }
}
