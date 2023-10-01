<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tabla extends Model
{
    use HasFactory;

    protected $table = 'Tabla';

    protected $primaryKey = 'id_tabla';

    public $timestamps = false;

    protected $fillable = [

        'id_tabla',
        'nombre_tabla',
        'id_matriz'

    ];

    //Representa la relación N-N entre Norma-Tabla
    public function normas() {

        return $this->belongsToMany(Norma::class, 'norma_tabla', 'id_tabla', 'id_norma');

    }

    //Representa la relación 1-N entre Tabla-Matriz
    public function matriz() {

        return $this->belongsTo(Matriz::class, 'id_matriz', 'id_matriz');

    }

    //Representa la relación N-N entre Tabla-Parametro-Metodologias
    public function parametros() {

        return $this->belongsToMany(Parametro::class, 'tabla_parametro_metodologia', 'id_tabla', 'id_parametro');

    }

    //Representa la relación N-N entre Tabla-Parametro-Metodologias
    public function metodologias() {

        return $this->belongsToMany(Metodologia::class, 'tabla_parametro_metodologia', 'id_tabla', 'id_metodologia');

    }
}
