<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movimientoahorro extends Model
{
    protected $fillable = [
        'cuenta_ahorro_id',
        'tipo',
        'monto',
        'concepto',
        'fecha',
    ];
    public function cuenta()
    {
        return $this->belongsTo(Cuentahorro::class);
    }
}
