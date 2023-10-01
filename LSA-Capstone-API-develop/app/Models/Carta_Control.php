<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carta_Control extends Model
{
    use HasFactory;

    protected $table = 'Carta_Control';

    protected $primaryKey = 'id_carta_control';

    protected $fillable = [

        'id_carta_control',
        'nombre_original_documento',
        'nombre_documento',
        'path_documento',
        'RUM',
        'nombre_parametro',

    ];

    //Representa la relación 1-N Muestra-Carta_Control
    public function muestra() {

        return  $this->belongsTo(Muestra::class);

    }

    //Representa la relación 1-N Parametro-Carta_Control
    public function parametro() {

        return $this->belongsTo(Parametro::class);
    }

}
