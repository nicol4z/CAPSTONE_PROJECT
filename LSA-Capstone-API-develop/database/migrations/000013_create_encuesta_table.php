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
        Schema::create('encuesta', function (Blueprint $table) {

            $table->id('id_encuesta');

            $table->string('pregunta_1', 255)
                  ->default('¿Qué tan receptivos hemos sido ante sus preguntas o inquietudes acerca de nuestros servicios de análisis?');

            $table->integer('puntaje_pregunta_1');

            $table->string('pregunta_2', 255)
                  ->default('¿Cómo calificaría la calidad de nuestros resultados?');

            $table->integer('puntaje_pregunta_2');

            $table->string('pregunta_3', 255)
                  ->default('En general, ¿qué tan satisfecho/a o insatisfecho/a está con UCN-LSA?');

            $table->integer('puntaje_pregunta_3');

            $table->string('observaciones', 512);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('encuesta');
    }
};
