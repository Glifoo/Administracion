<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $fillable = [
        'nombre',
        'detalle',
        'costo',
        'cantidad',
        'trabajo_id',
        'medida_id',
    ];

    /**
     * 
     * realciones
     */
    
    public function trabajo()
    {
        return $this->belongsTo(Trabajo::class, 'trabajo_id');
    }

    public function medida()
    {
        return $this->belongsTo(Medida::class, 'medida_id');
    }
}
