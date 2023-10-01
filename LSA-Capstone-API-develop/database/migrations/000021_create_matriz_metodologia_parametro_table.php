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
        Schema::create('matriz_metodologia_parametro', function (Blueprint $table) {

            $table->unsignedBigInteger('id_matriz');

            $table->unsignedBigInteger('id_metodologia');

            $table->unsignedBigInteger('id_parametro');

            $table->foreign('id_matriz')->references('id_matriz')->on('Matriz')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_metodologia')->references('id_metodologia')->on('Metodologia')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_parametro')->references('id_parametro')->on('Parametro')
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
        Schema::dropIfExists('matriz__metodologia__parametros');
    }
};
