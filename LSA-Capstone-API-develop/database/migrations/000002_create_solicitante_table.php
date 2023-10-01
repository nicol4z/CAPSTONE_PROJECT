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
        Schema::create('solicitante', function (Blueprint $table) {

            $table->string('rut_solicitante', 255)
                  ->primary()
                  ->unique();

            $table->string('nombre', 255);

            $table->string('primer_apellido', 255);

            $table->string('segundo_apellido', 255);

            $table->boolean('estado');

            $table->string('correo', 255);

            $table->string('telefono', 255);

            $table->string('direccion_contacto_proveedores', 255);

            $table->string('fono_contacto_proveedores', 255)
                  ->nullable();

            $table->string('direccion_envio_factura', 255);

            $table->string('tipo_cliente', 255);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitante');
    }
};
