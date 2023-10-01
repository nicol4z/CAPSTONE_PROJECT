<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado_Parametro extends Model
{
    use HasFactory;

    protected $table = 'Empleado_Parametro';

    public $timestamps = false;

    protected $fillable = [

        'rut_empleado',
        'nombre_parametro',

    ];
}
