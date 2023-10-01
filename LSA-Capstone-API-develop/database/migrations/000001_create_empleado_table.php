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
        Schema::create('empleado', function (Blueprint $table) {

            $table->string('rut_empleado', 255)
                  ->primary()
                  ->unique();

            $table->string('nombre', 255);

            $table->string('apellido', 255);

            $table->string('correo', 255);

            $table->string('rol', 255);

            $table->string('telefono_movil', 255);

            $table->string('telefono_emergencia', 255)
                  ->nullable();

            $table->string('tipo_trabajador', 255);

            $table->boolean('estado');

            $table->date('fecha_inicio_vacaciones')
                  ->nullable();

            $table->date('fecha_termino_vacaciones')
                  ->nullable();

            $table->integer('dias_vacaciones_disponibles')
                  ->nullable();

            $table->integer('dias_administrativos')
                  ->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empleado');
    }
};
