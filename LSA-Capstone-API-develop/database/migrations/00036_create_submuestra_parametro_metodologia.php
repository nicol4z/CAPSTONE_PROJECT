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
        Schema::create('submuestra_parametro_metodologia', function (Blueprint $table) {

            $table->unsignedBigInteger('id_submuestra');

            $table->unsignedBigInteger('id_parametro');

            $table->unsignedBigInteger('id_metodologia');

            $table->foreign('id_submuestra')->references('id_submuestra')->on('Submuestra')
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
        //
    }
};
