<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metodologia_Parametro extends Model
{
    use HasFactory;

    protected $table = 'Metodologia_Parametro';

    public $timestamps = false;

    protected $fillable = [

        'id_metodologia',
        'id_parametro',

    ];

}
