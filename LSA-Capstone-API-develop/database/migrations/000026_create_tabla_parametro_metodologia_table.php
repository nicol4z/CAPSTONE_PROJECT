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
        Schema::create('tabla_parametro_metodologia', function (Blueprint $table) {

            $table->unsignedBigInteger('id_tabla');

            $table->unsignedBigInteger('id_parametro');

            $table->unsignedBigInteger('id_metodologia');

            $table->foreign('id_tabla')->references('id_tabla')->on('Tabla')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_parametro')->references('id_parametro')->on('Parametro')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_metodologia')->references('id_metodologia')->on('Metodologia')
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
        Schema::dropIfExists('tabla_parametro_metodologia');
    }
};
