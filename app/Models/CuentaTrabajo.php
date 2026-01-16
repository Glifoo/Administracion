<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuentaTrabajo extends Model
{
    protected $fillable = [
        'cuenta_id',
        'trabajo_id'
    ];

    public function cuenta()
    {
        return $this->belongsTo(Cuentahorro::class);
    }

    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class);
    }
}
