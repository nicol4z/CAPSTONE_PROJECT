<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submuestra extends Model
{
    use HasFactory;

    protected $table = 'Submuestra';

    protected $primaryKey = 'id_submuestra';

    public $timestamps = false;

    protected $fillable = [

        'id_submuestra',
        'identificador',
        'orden',
        'RUM',
        'id_parametro',
        'id_metodologia',

    ];


    //Representa la relación 1-N entre Muestra-Submuestra
    public function muestra() {

        return $this->belongsTo(Muestra::class, 'RUM', 'RUM');

    }


    //Representa la relación N-N entre Empleado-Submuestra
    public function empleados() {

        return $this->belongsToMany(Empleado::class, 'empleado_submuestra', 'id_submuestra', 'rut_empleado');

    }

    //Representa la relación 1-N entre Parametro-Submuestra
    public function parametro () {

        return $this->belongsTo(Parametro::class, 'id_parametro', 'id_parametro');

    }

    //Representa la relación 1-N entre Metodologia-Submuestra
    public function metodologia () {

        return $this->belongsTo(Metodologia::class, 'id_metodologia', 'id_metodologia');

    }

}
