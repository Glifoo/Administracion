<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Swindon\FilamentHashids\Traits\HasHashid;

class Trabajo extends Model
{
    use HasHashid;
    protected $fillable = [
        'trabajo',
        'descripcion',
        'cantidad',
        'estado',
        'manobra',
        'ganancia',
        'gananciaefectivo',
        'iva',
        'ivaefectivo',
        'Costofactura',
        'Costoproduccion',
        'Costofinal',
        'cliente_id',
        'cuenta',
    ];

    /**
     * 
     * realciones
     */

    public function cliente()
    {
        return $this->belongsTo(Client::class, 'cliente_id');
    }

    public function ordenesPago()
    {
        return $this->hasMany(Ordenpago::class, 'trabajo_id');
    }

    public function insumos()
    {
        return $this->hasMany(Insumo::class, 'trabajo_id');
    }
    
    public function cuenta()
    {
        return $this->hasOne(CuentaTrabajo::class);
    }
    /**
     * 
     * Metodos
     */
}
