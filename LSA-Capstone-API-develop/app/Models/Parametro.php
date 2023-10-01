<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parametro extends Model
{
    use HasFactory;

    protected $table = 'Parametro';

    protected $primaryKey = 'id_parametro';

    public $timestamps = false;

    protected $fillable = [

        'id_parametro',
        'nombre_parametro',

    ];

    //Representa la relación 1-N Parametro-Carta_Control
    public function cartas() {

        return $this->hasMany(Carta_Control::class);

    }

    //Representa la relación N-N entre Empleado-Parametro
    public function empleados() {

        return $this->belongsToMany(Empleado::class, 'empleado_parametro');

    }

    //Representa la relación N-N entre Matriz-Parametro
    public function matrices() {

        return $this->belongsToMany(Matriz::class, 'matriz_metodologia_parametro', 'id_parametro', 'id_matriz');

    }

    //Representa la relación N-N entre Metodologia-Parametro
    public function metodologias() {

        return $this->belongsToMany(Metodologia::class, 'metodologia_parametro', 'id_parametro', 'id_metodologia');

    }

    //Representa la relación N-N entre Muestra-Parametro
    public function muestras() {

        return $this->belongsToMany(Muestra::class, 'muestra_parametro_metodologia', 'id_parametro', 'RUM');

    }

    //Representa la relación 1-N entre Parametro-Submuestra
    public function submuestras () {

        return $this->hasMany(Submuestra::class, 'id_parametro', 'id_parametro');

    }

    //Representa la relación N-N entre Tabla-Parametro
    public function tablas() {

        return $this->belongsToMany(Tabla::class, 'Tabla_Parametro_Metodologia', 'id_parametro', 'id_tabla');

    }

}
