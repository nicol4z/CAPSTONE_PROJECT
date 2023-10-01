<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metodologia extends Model
{
    use HasFactory;

    protected $table = 'Metodologia';

    protected $primaryKey = 'id_metodologia';

    public $timestamps = false;

    protected $fillable = [

        'id_metodologia',
        'nombre_metodologia',
        'detalle_metodologia',

    ];

    //Representa la relación N-N entre Empleado-Metodologia
    public function empleados() {

        return $this->belongsToMany(Empleado::class, 'empleado_metodologia', 'id_metodologia', 'rut_empleado');

    }

    //Representa la relación N-N entre Matriz-Metodologia
    public function matrices() {

        return $this->belongsToMany(Matriz::class, 'matriz_metodologia_parametro', 'id_metodologia', 'id_matriz');

    }

    //Representa la relación N-N entre Muestra-Metodologia
    public function muestras() {

        return $this->belongsToMany(Muestra::class, 'muestra_parametro_metodologia', 'id_metodologia', 'RUM');

    }

    //Representa la relación 1-N entre Metodologia-Submuestra
    public function submuestras () {

        return $this->hasMany(Submuestra::class, 'id_metodologia', 'id_metodologia');

    }

    //Representa la relación N-N entre Metodologia-Parametro
    public function parametros() {

        return $this->belongsToMany(Parametro::class, 'metodologia_parametro', 'id_metodologia', 'id_parametro');

    }

    //Representa la relación N-N entre Tabla-Metodologia
    public function tablas() {

        return $this->belongsToMany(Tabla::class, 'Tabla_Parametro_Metodologia', 'id_metodologia', 'id_tabla');

    }

}
