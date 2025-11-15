<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('xp_reward')->default(0);
            $table->enum('rarity', ['common','rare','epic','legendary'])->default('common');
            $table->string('icon')->nullable();
            $table->enum('status', ['locked','unlocked','in_progress'])->default('locked');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
