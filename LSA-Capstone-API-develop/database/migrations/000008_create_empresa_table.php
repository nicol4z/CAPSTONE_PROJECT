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
        Schema::create('empresa', function (Blueprint $table) {

            $table->string('rut_empresa', 255)
                  ->primary()
                  ->unique();

            $table->string('nombre_empresa', 255);

            $table->string('nombre_abreviado', 255);

            $table->boolean('estado');

            $table->string('correo', 255)
                  ->unique();

            $table->string('razon_social', 255);

            $table->string('giro', 255);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresa');
    }
};
