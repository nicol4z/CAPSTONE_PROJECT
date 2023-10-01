<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submuestra_Parametro_Metodologia extends Model
{
    use HasFactory;

    protected $table = 'Submuestra_Parametro_Metodologia';

    public $timestamps = false;

    protected $fillable = [

        'id_submuestra',
        'id_parametro',
        'id_metodologia',

    ];

}
