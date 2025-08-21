<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Swindon\FilamentHashids\Traits\HasHashid;

class Ordenpago extends Model
{
    use HasHashid;

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
