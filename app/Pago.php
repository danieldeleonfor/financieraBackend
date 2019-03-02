<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = ['cliente_id', 'prestamo_id', 'monto', 'mes', 'mora', 
    'monto_a_pagar', 'fecha_pago', 'fecha_limite_pago', 'estado'];

    public function moraPrestamo() {
        return $this->belongsTo('App\Prestamo')->select('mora_porciento');
    }

    public function prestamo() {
        return $this->belongsTo('App\Prestamo');
    }
   
}
