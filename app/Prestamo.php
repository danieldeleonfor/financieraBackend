<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $fillable = ['solicitud_id', 'prestamista_id', 'cliente_id', 
    'monto_total', 'meses', 'mora_porciento', 'interes_porciento', 'estado'];

    public function prestamista() {
        return $this->belongsTo('App\User', 'prestamista_id', 'id');
    }

    public function cliente() {
        return $this->belongsTo('App\Cliente', 'cliente_id');
    }

    public function pagos() {
        return $this->hasMany('App\Pago');
    }
}
