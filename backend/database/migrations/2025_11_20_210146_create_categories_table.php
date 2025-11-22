<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            // user_id for null -> categoria do sistema, caso contrário categoria do usuário
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('name');
            $table->string('icon')->nullable(); 
            $table->string('color')->nullable();
            $table->timestamps();

            // se quiser FK com users descomente e ajuste
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
