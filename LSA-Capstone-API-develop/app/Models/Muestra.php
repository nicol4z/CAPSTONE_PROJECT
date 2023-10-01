<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muestra extends Model
{
    use HasFactory;

    protected $table = 'Muestra';

    protected $primaryKey = 'RUM';

    public $timestamps = false;

    protected $fillable = [

        'RUM',
        'rut_empresa',
        'nombre_empresa',
        'id_ciudad',
        'direccion_empresa',
        'muestreado_por',
        'cantidad_muestras',
        'prioridad',
        'fecha_muestreo',
        'hora_muestreo',
        'temperatura_transporte',
        'fecha_entrega',
        'fecha_ingreso',
        'hora_ingreso',
        'rut_transportista',
        'nombre_transportista',
        'patente_vehiculo',
        'tipo_pago',
        'valor_neto',
        'estado',
        'rut_empleado',
        'id_matriz',
        'id_norma',
        'id_orden_compra',
        'id_encuesta',

    ];

    //Representa la relación de N-N entre Muestra-Cotizacion
    public function cotizaciones() {

        return $this->belongsToMany(Cotizacion::class, 'muestra_cotizacion', 'RUM', 'id_cotizacion');

    }

    //Representa la relación N-N entre Empleado-Muestra
    public function empleados() {

        return $this->belongsToMany(Empleado::class, 'empleado_muestra', 'RUM', 'rut_empleado');

    }

    //Representa la relación N-N entre Muestra-Parametro
    public function parametros() {

        return $this->belongsToMany(Parametro::class, 'muestra_parametro_metodologia', 'RUM', 'id_parametro');

    }

    //Representa la relación N-N entre Muestra-Metodologia
    public function metodologias() {

        return $this->belongsToMany(Metodologia::class, 'muestra_parametro_metodologia', 'RUM', 'id_metodologia');

    }

    //Representa la relación 1-N entre Muestra-Submuestra
    public function submuestras() {

        return $this->hasMany(Submuestra::class, 'RUM', 'RUM');

    }

    //Representa la relación 1-N entre Muestra-Norma
    public function norma() {

        return $this->belongsTo(Norma::class, 'id_norma', 'id_norma');

    }

    //Representa el atributo multivaluado Telefono_Transportista
    public function telefonos_transportistas() {

        return $this->hasMany(Muestra_Telefono_Transportista::class, 'RUM', 'RUM');

    }

    //Representa el atributo multivaluado Observaciones
    public function observaciones() {

        return $this->hasMany(Muestra_Observaciones::class, 'RUM', 'RUM');

    }

    //Representa la relación 1-N Muestra-Matriz
    public function matriz() {

        return $this->belongsTo(Matriz::class, 'id_matriz','id_matriz');

    }

    //Representa la relación 1-N Muestra-Carta_Control
    public function cartas() {

        return  $this->hasMany(Carta_Control::class);
    }

    // Representa la relación 1-1 Muestra-Orden_Compra
    public function orden_compra() {

        return $this->belongsTo(Orden_Compra::class, 'id_orden_compra', 'id_orden_compra');

    }

    // Representa la relación 1-1 Muestra-Orden_Compra
    public function encuesta() {

        return $this->belongsTo(Encuesta::class, 'id_encuesta', 'id_encuesta');

    }

}
