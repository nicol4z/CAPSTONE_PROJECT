<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    use HasFactory;

    protected $table = 'Ciudad';

    protected $primaryKey = 'id_ciudad';

    public $timestamps = false;

    protected $fillable = [

        'id_ciudad',
        'nombre_ciudad',
        'direccion',

    ];

    //Representa la relación (N,N) entre Ciudad-Empresa
    public function empresas() {

        return $this->belongsToMany(Empresa::class, 'empresa_ciudad', 'id_ciudad', 'rut_empresa');

    }

    //Representa la relación (N,N) entre Ciudad-Solicitante
    public function solicitantes() {

        return $this->belongsToMany(Solicitante::class, 'empresa_ciudad_solicitante', 'id_ciudad', 'rut_solicitante');

    }



}
