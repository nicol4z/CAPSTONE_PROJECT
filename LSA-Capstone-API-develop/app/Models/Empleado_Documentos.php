<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado_Documentos extends Model
{
    use HasFactory;

    protected $table = 'Empleado_Documentos';

    protected $primaryKey = 'id_documento';

    public $timestamps = false;

    protected $fillable = [

        'id_documento',
        'fecha_subida',
        'rut_empleado',
        'nombre_original_documento',
        'nombre_documento',
        'path_documento'

    ];

    //Representa el atributo multivaluado Documentos
    public function empleado() {

        return $this->belongsTo(Empleado::class, 'rut_empleado', 'rut_empleado');

    }
}
