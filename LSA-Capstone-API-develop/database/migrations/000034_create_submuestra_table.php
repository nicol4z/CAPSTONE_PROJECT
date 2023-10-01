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
        Schema::create('submuestra', function (Blueprint $table) {

            $table->id('id_submuestra');

            $table->string('identificador', 255);

            $table->integer('orden');

            $table->unsignedBigInteger('RUM');

            $table->unsignedBigInteger('id_parametro');

            $table->unsignedBigInteger('id_metodologia');

            $table->foreign('RUM')->references('RUM')->on('Muestra')
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
        Schema::dropIfExists('submuestra');
    }
};
