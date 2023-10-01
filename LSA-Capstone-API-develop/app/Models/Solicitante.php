<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitante extends Model
{
    use HasFactory;

    protected $table = 'Solicitante';

    protected $primaryKey = 'rut_solicitante';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [

        'rut_solicitante',
        'nombre',
        'primer_apellido',
        'segundo_apellido',
        'estado',
        'correo',
        'telefono',
        'direccion_contacto_proveedores',
        'fono_contacto_proveedores',
        'direccion_envio_factura',
        'tipo_cliente',

    ];

    //Representa la relación (N,N) entre Empresa-Solicitante
    public function empresas() {

        return $this->belongsToMany(Empresa::class, 'empresa_ciudad_solicitante', 'rut_solicitante', 'rut_empresa');

    }

    //Representa la relación (N,N) entre Solicitante-Ciudad
    public function ciudades() {

        return $this->belongsToMany(Ciudad::class, 'empresa_ciudad_solicitante', 'rut_solicitante', 'id_ciudad');

    }

    //Representa la relación (1,N) entre Solicitante-Encuesta
    public function encuestas() {

        return $this->belongsToMany(Encuesta::class, 'solicitante_encuesta', 'rut_solicitante', 'id_encuesta');

    }

    //Representa la relación (1,N) entre Solicitante-Cotizacion
    public function cotizaciones() {

        return $this->hasMany(Cotizacion::class, 'rut_solicitante','rut_solicitante');

    }

}
