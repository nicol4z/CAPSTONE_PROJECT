<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitante_Encuesta extends Model
{
    use HasFactory;

    protected $table = 'Solicitante_Encuesta';

    public $timestamps = false;

    protected $fillable = [

        'rut_solicitante',
        'id_encuesta',

    ];
}
