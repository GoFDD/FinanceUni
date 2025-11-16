<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::create('pluggy_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->string('item_id'); // ID da Pluggy
            $table->string('institution')->nullable(); // Nome do banco
            $table->string('status')->nullable(); // ok, syncing, error, etc
            $table->string('connector_id')->nullable(); // qual banco

            $table->timestamp('last_sync')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pluggy_items');
    }
};
