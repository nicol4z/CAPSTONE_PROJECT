<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa_Ciudad extends Model
{
    use HasFactory;

    protected $table = 'Empresa_Ciudad';

    public $timestamps = false;

    protected $fillable = [

        'rut_empresa',
        'id_ciudad',

    ];

}
