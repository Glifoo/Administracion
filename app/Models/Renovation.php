<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Renovation extends Model
{
    protected $fillable = [
        'fecha',
        'meses',
        'estado',
        'suscripcion_id',
    ];

    /**
     * 
     * realciones
     */

    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class, 'suscripcion_id');
    }
}
