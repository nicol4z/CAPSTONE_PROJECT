<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden_Compra extends Model
{
    use HasFactory;

    protected $table = 'Orden_Compra';

    protected $primaryKey = 'id_orden_compra';

    public $timestamps = false;

    protected $fillable = [

        'id_orden_compra',
        'fecha_ingreso',
        'nombre_original_documento',
        'nombre_documento',
        'path_documento',

    ];

    // Representa la relación entre Muestra-Orden_Compra
    public function muestra () {

        return $this->hasOne(Muestra::class, 'id_orden_compra', 'id_orden_compra');

    }

}
