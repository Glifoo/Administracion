<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Swindon\FilamentHashids\Traits\HasHashid;

class Ordencompra extends Model
{
    use HasHashid;
    protected $fillable = [
        'total',
        'cuenta',
        'saldo',
        'estado',
        'insumo_id',
    ];

    public function insumo()
    {
        return $this->belongsTo(Insumo::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pagoinsumo::class);
    }
}
