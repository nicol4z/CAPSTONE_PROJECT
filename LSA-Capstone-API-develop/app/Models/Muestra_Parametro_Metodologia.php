<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muestra_Parametro_Metodologia extends Model
{
    use HasFactory;

    protected $table = 'Muestra_Parametro_Metodologia';

    public $timestamps = false;

    protected $fillable = [

        'RUM',
        'id_parametro',
        'id_metodologia'

    ];
}
