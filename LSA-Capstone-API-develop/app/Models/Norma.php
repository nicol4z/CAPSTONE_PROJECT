<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Norma extends Model
{
    use HasFactory;

    protected $table = 'Norma';

    protected $primaryKey = 'id_norma';

    public $timestamps = false;

    protected $fillable = [

        'id_norma',
        'nombre_norma',

    ];


    //Representa la relación 1-N entre Muestra-Norma
    public function submuestras () {

        return $this->hasMany(Submuestra::class, 'id_norma', 'id_norma');

    }
    //Representa la relación N-N entre Norma-Tabla
    public function tablas() {

        return $this->belongsToMany(Tabla::class, 'norma_tabla', 'id_norma', 'id_tabla');

    }

    //Representa la relación N-N entre Norma-Matriz
    public function matrices() {

        return $this->belongsToMany(Matriz::class, 'norma_matriz', 'id_norma', 'id_matriz');

    }

}
