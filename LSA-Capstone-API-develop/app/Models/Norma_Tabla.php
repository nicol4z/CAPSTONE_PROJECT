<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Norma_Tabla extends Model
{
    use HasFactory;

    protected $table = 'Norma_Tabla';

    public $timestamps = false;

    protected $fillable = [

        'id_norma',
        'id_tabla',

    ];

}
