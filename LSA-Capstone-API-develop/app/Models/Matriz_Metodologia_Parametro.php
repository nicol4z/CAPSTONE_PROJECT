<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matriz_Metodologia_Parametro extends Model
{
    use HasFactory;

    protected $table = 'Matriz_Metodologia_Parametro';

    public $timestamps = false;

    protected $fillable = [

        'id_matriz',
        'id_metodologia',
        'id_parametro',

    ];

}
