<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matriz extends Model
{
    use HasFactory;

    protected $table = 'Matriz';

    protected $primaryKey = 'id_matriz';



    public $timestamps = false;

    protected $fillable = [

        'id_matriz',
        'nombre_matriz',

    ];

    //Representa la relación 1-N Muestra-Matriz
    public function muestras() {

        return $this->hasMany(Muestra::class, 'id_matriz', 'id_matriz');

    }

    //Representa la relación N-N entre Norma-Matriz
    public function normas() {

        return $this->belongsToMany(Norma::class, 'norma_matriz', 'id_matriz', 'id_norma');

    }

    //Representa la relación 1-N entre Tabla-Matriz
    public function tablas() {

        return $this->hasMany(Tabla::class, 'id_matriz', 'id_matriz');

    }

    //Representa la relación N-N entre Matriz-Metodologia
    public function metodologias() {

        return $this->belongsToMany(Metodologia::class, 'matriz_metodologia_parametro', 'id_matriz', 'id_metodologia');

    }

    //Representa la relación N-N entre Matriz-Parametro
    public function parametros() {

        return $this->belongsToMany(Parametro::class, 'matriz_metodologia_parametro', 'id_matriz', 'id_parametro');

    }
}
