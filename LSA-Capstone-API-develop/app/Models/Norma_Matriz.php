<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Norma_Matriz extends Model
{
    use HasFactory;

    protected $table = 'Norma_Matriz';

    public $timestamps = false;

    protected $fillable = [

        'id_norma',
        'id_matriz',

    ];

}
