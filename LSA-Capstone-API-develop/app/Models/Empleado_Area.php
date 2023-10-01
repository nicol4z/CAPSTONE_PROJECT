<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado_Area extends Model
{
    use HasFactory;

    protected $table = 'Empleado_Area';

    public $timestamps = false;

    protected $fillable = [

        'rut_empleado',
        'id_area',
        'tipo_analisis',

    ];

}
