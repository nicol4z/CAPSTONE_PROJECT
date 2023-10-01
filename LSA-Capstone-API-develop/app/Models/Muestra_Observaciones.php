<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muestra_Observaciones extends Model
{
    use HasFactory;

    protected $table = 'Muestra_Observaciones';

    public $timestamps = false;

    protected $fillable = [

        'RUM',
        'rut_empleado',
        'observaciones',
        'fecha_observacion',
        'hora_observacion',

    ];

    //Representa el atributo multivaluado Observaciones
    public function muestra() {

        return $this->belongsTo(Muestra::class,'RUM', 'RUM');

    }

}
