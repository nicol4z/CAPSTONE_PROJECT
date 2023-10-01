<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado_Muestra extends Model
{
    use HasFactory;

    protected $table = 'Empleado_Muestra';

    public $timestamps = false;

    protected $fillable = [

        'rut_empleado',
        'RUM',
        'orden_de_analisis',
        'id_parametro',
        'fecha_entrega',
        'estado'

    ];
}
