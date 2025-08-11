<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $fillable = [
        'fecha',
        'pago',
        'ordenpago_id',
    ];

    /**
     * 
     * realciones
     */
    public function ordenPago()
    {
        return $this->belongsTo(Ordenpago::class, 'ordenpago_id');
    }
}
