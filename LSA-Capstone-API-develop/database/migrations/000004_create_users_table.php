<?php

use App\Models\User;

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
        Schema::create('users', function (Blueprint $table) {

            $table->id();

            $table->string('nombre');

            $table->string('apellido');

            $table->string('email')
                  ->unique();

            $table->string('rut')
                  ->nullable();

            $table->string('rut_solicitante')
                  ->nullable();

            $table->foreign('rut')->references('rut_empleado')->on('Empleado');

            $table->foreign('rut_solicitante')->references('rut_solicitante')->on('Solicitante');

            $table->timestamp('email_verified_at')
                  ->nullable();

            $table->string('password');

            $table->tinyInteger('role')
                  ->default(0);

            $table->boolean('estado')
                  ->default(true);

            $table->rememberToken();

            $table->timestamps();

        });

        $user = User::create([
            'nombre' => 'Nombre Admin',
            'apellido' => 'Apellido Admin',
            'email' => "test@test.com",
            'password' => bcrypt('test'),
            'role' => 0,

        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
