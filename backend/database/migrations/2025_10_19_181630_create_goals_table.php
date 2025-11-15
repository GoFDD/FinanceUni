<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();

            //  meta criada pelo usuário
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade');

            //  meta criada pelo sistema (para todos os usuários)
            $table->boolean('is_system_goal')->default(false);

            //  meta do sistema,  identificador único
            $table->string('system_key')->nullable()
                ->unique()
                ->comment('Identificador único interno para metas do sistema (ex: save_1000, track_expenses_30_days)');

            $table->string('title');
            $table->string('category')->nullable();
            $table->decimal('target_value', 12, 2)->nullable();
            $table->decimal('current_value', 12, 2)->default(0);

            // Recompensas de XP só para metas do sistema
            $table->integer('xp_reward')->default(0);
            $table->boolean('grants_achievement')->default(false);

            $table->enum('status', ['active', 'completed', 'canceled'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
