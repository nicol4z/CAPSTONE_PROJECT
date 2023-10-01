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
        Schema::create('muestra_cotizacion', function (Blueprint $table) {

            $table->unsignedBigInteger('RUM');

            $table->unsignedBigInteger('id_cotizacion');

            $table->foreign('RUM')->references('RUM')->on('Muestra')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            $table->foreign('id_cotizacion')->references('id_cotizacion')->on('Cotizacion')
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
        Schema::dropIfExists('muestra_cotizacion');
    }
};
