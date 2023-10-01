<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado_Submuestra extends Model
{
    use HasFactory;

    protected $table = 'Empleado_Submuestra';

    public $timestamps = false;

    protected $fillable = [

        'rut_empleado',
        'identifcador',
        'id_parametro',
        'valor_resultado',
        'unidad',
        'fecha_inicio_analisis',
        'hora_inicio_analisis',
        'fecha_termino_analisis',
        'hora_termino_analisis',
        'fecha_entrega',

    ];

}
