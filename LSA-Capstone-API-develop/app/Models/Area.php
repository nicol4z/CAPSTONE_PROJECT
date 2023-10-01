<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'Area';

    protected $primaryKey = 'id_area';

    public $timestamps = false;

    protected $fillable = [

        'id_area',
        'nombre_area',

    ];

    //Representa la relación (N,N) entre Empleado-Area
    public function empleados() {

        return $this->belongsToMany(Empleado::class, 'empleado_area', 'id_area', 'rut_empleado');

    }

}
