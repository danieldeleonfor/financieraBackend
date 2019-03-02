<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tasa extends Model
{
    protected $fillable = ['monto_inicio', 'monto_final', 'tasa_porciento', 'tipo'];
}
