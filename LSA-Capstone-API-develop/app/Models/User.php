<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'nombre',
        'apellido',
        'email',
        'password',
        'role',
        'estado',
        'rut',
        'rut_solicitante'

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [

        'password',
        'remember_token',

    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



       // Representa la relación 1-1 entre Empleado-Muestra
       public function empleado() {

        return $this->belongsTo(Empleado::class, 'rut','rut_empleado');

    }
    public function solicitante() {

        return $this->belongsTo(Solicitante::class, 'rut_solicitante','rut_solicitante');

    }
}
