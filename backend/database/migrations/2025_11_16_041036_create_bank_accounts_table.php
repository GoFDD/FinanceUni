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
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->string('pluggy_item_id');
            $table->string('pluggy_account_id')->unique();

            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('subtype')->nullable();
            $table->string('number')->nullable();

            $table->decimal('balance', 14, 2)->default(0);
            $table->string('currency_code')->default('BRL');

            $table->string('institution_name')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
