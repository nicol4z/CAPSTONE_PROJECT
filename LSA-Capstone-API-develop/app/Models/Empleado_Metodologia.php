<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado_Metodologia extends Model
{
    use HasFactory;

    protected $table = 'Empleado_Metodologia';

    protected $fillable = [

        'rut_empleado',
        'nombre_metodologia'

    ];

}
