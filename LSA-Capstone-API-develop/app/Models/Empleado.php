<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    protected $table = 'Empleado';

    protected $primaryKey = 'rut_empleado';

    protected $keyType = 'string';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [

        'rut_empleado',
        'nombre',
        'apellido',
        'correo',
        'contraseña',
        'rol',
        'tipo_trabajador',
        'telefono_movil',
        'telefono_emergencia',
        'estado',
        'fecha_inicio_vacaciones',
        'fecha_termino_vacaciones',
        'dias_vacaciones_disponibles',
        'dias_administrativos',

    ];

    // Representa la relación 1-1 entre Empleado-User
    public function usuario() {

        return $this->hasOne(User::class, 'rut_empleado', 'rut_empleado');

    }

    //Representa la relación (N,N) entre Empleado-Area
    public function areas() {

        return $this->belongsToMany(Area::class, 'empleado_area', 'rut_empleado', 'id_area');

    }

    // Representa la relación N-N entre Empleado-Muestra
    public function muestras() {

        return $this->belongsToMany(Muestra::class, 'empleado_muestra', 'rut_empleado', 'RUM');

    }

    // Representa la relación N-N entre Empleado-Submuestra
    public function submuestras() {

        return $this->belongsToMany(Submuestra::class, 'empleado_submuestra', 'rut_empleado', 'id_submuestra');

    }

    // Representa el atributo multivaluado Documentos
    public function documentos() {

        return $this->hasMany(Empleado_Documentos::class, 'rut_empleado', 'rut_empleado');

    }

    //Representa la relación N-N entre Empleado-Parametro
    public function parametros() {

        return $this->belongsToMany(Parametro::class, 'empleado_parametro');

    }

    //Representa la relación N-N entre Empleado-Metodologia
    public function metodologias() {

        return $this->belongsToMany(Metodologia::class, 'empleado_metodologia', 'rut_empleado', 'id_metodologia');

    }

}
