<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Swindon\FilamentHashids\Traits\HasHashid;

class Pago extends Model
{
    use HasHashid;

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
