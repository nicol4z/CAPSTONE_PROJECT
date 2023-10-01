<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;



    protected $primaryKey = 'id_rol';

    protected $fillable = [

        'id_rol',
        'descripcion',

    ];
}
