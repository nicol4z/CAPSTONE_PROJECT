<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encuesta extends Model
{
    use HasFactory;

    protected $table = 'Encuesta';

    protected $primaryKey = 'id_encuesta';

    public $timestamps = false;

    protected $fillable = [

        'id_encuesta',
        'pregunta_1',
        'puntaje_pregunta_1',
        'pregunta_2',
        'puntaje_pregunta_2',
        'pregunta_3',
        'puntaje_pregunta_3',
        'observaciones',

    ];

    //Representa la relación (1,N) entre Solicitante-Encuesta
    public function solicitantes() {

        return $this->belongsToMany(Solicitante::class, 'solicitante_encuesta', 'id_encuesta', 'rut_solicitante');

    }

    // Representa la relación 1-1 Muestra-Orden_Compra
    public function muestra() {

        return $this->hasOne(Muestra::class, 'id_encuesta', 'id_encuesta');

    }

}
