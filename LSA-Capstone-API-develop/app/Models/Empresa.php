<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $table = 'Empresa';

    protected $primaryKey = 'rut_empresa';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [

        'rut_empresa',
        'nombre_empresa',
        'nombre_abreviado',
        'estado',
        'correo',
        'razon_social',
        'giro',

    ];

    //Representa la relación (1,N) entre Empresa-Ciudad
    public function ciudades() {

        return $this->belongsToMany(Ciudad::class, 'empresa_ciudad', 'rut_empresa', 'id_ciudad');

    }

    //Representa la relación (N,N) entre Empresa-Solicitante
    public function solicitantes(){

        return $this->belongsToMany(Solicitante::class, 'empresa_ciudad_solicitante', 'rut_empresa', 'rut_solicitante');

    }


}
