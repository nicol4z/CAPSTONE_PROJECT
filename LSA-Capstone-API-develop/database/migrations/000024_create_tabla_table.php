<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabla', function (Blueprint $table) {

            $table->id('id_tabla');

            $table->string('nombre_tabla', 255);

            $table->unsignedBigInteger('id_matriz');

            $table->foreign('id_matriz')->references('id_matriz')->on('Matriz')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tabla');
    }
};
