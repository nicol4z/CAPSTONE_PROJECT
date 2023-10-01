<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cotizacion extends Model
{
    use HasFactory;

    protected $table = 'Cotizacion';

    protected $primaryKey = 'id_cotizacion';

    public $timestamps = false;

    protected $fillable = [

        'id_cotizacion',
        'numero_cotizacion',
        'fecha_ingreso',
        'fecha_emision',
        'nombre_original_documento',
        'nombre_documento',
        'path_documento',
        'rut_solicitante',

    ];

    //Representa la relación (1,N) entre Solicitante-Cotizacion
    public function solicitante() {

        return $this->belongsTo(solicitante::class);

    }

    //Representa la relación de (1,N) entre Muestra-Cotizacion
    public function muestras() {

        return $this->belongsToMany(Muestra::class, 'muestra_cotizacion', 'id_cotizacion', 'RUM');

    }

}
