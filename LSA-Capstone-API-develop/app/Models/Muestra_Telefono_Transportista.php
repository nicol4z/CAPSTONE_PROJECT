<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Muestra_Telefono_Transportista extends Model
{
    use HasFactory;

    protected $table = 'Muestra_Telefono_Transportista';

    public $timestamps = false;

    protected $fillable = [

        'RUM',
        'telefono_transportista',

    ];

    //Representa el atributo multivaluado Telefono_Transportista
    public function muestra(){

        return $this->belongsTo(Muestra::class, 'RUM', 'RUM');

    }

}
