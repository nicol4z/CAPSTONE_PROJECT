<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabla_Parametro_Metodologia extends Model
{
    use HasFactory;

    protected $table = 'Tabla_Parametro_Metodologia';

    public $timestamps = false;

    protected $fillable = [

        'id_tabla',
        'id_parametro',
        'id_metodologia',

    ];

}
