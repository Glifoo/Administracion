<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordenpago extends Model
{
    protected $fillable = [
        'total',
        'cuenta',
        'saldo',
        'estado',
        'trabajo_id',
    ];

    /**
     * 
     * realciones
     */
    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class, 'trabajo_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'ordenpago_id');
    }
}
