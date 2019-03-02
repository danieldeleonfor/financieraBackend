<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    protected $table = 'solicitudes';

    protected $fillable = ['cliente_id', 'monto', 'meses', 'motivo', 'estado'];

    public function cliente() {
        return $this->belongsTo('App\Cliente');
    }

    public function prestamo() {
        return $this->hasOne('App\Prestamo');
    }
}
