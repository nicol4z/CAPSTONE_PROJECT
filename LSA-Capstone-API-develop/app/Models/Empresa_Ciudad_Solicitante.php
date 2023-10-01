<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa_Ciudad_Solicitante extends Model
{
    use HasFactory;

    protected $table = 'Empresa_Ciudad_Solicitante';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [

        'rut_empresa',
        'id_ciudad',
        'rut_solicitante',

    ];

}
