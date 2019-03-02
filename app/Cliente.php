<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['nombre', 'apellido', 'email', 'cedula', 'direccion', 
    'sexo', 'telefono', 'empresa', 'cargo', 'sueldo', "estado_civil"];

    public $valocacionPorPagos;

    public function financiamientos() {
        return $this->hasMany('App\Prestamo');
    }

    public function solicitudes() {
        return $this->hasMany('App\Solicitud');
    }

    public function pagos() {
        return $this->hasMany('App\Pago');
    }


    public function getValoracionPorPagosAttribute()
    {
        $pagos = $this->pagos()->where('estado', 'pago')->count() * 2;
        $pagosAtrasado = $this->pagos()->where('estado', 'pago_atrasado')->count();
        $atrasado = $this->pagos()->where('estado', 'atrasado')->count();
        return ($pagos + $pagosAtrasado) - $atrasado;
    }
}
