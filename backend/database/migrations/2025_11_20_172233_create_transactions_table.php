<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            // Usuário dono da transação
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['income', 'expense'])->default('income'); // Tipo da transação
            $table->enum('source', ['manual', 'bank'])->default('manual'); // Origem da transação (manual ou banco/pluggy)
            $table->unsignedBigInteger('category_id')->nullable(); // Categoria (receitas e despesas)
            $table->string('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('date')->nullable();
            $table->boolean('is_recurring')->default(false); // Flags
            $table->string('external_id')->nullable(); // Integração pluggy
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); //tabela users
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
