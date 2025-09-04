<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Swindon\FilamentHashids\Traits\HasHashid;

class Client extends Model
{
    use HasHashid;

    protected $fillable = [
        'nombre',
        'apellido',
        'contacto',
        'nit',
        'email',
        'usuario_id',
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
    
    public function trabajos()
    {
        // Especificar la clave foránea explícitamente
        return $this->hasMany(Trabajo::class, 'cliente_id');
    }

    /**
     * Metodos 
     */
    public static function optionsForAuthUser(): array
    {
        return Auth::user()
            ->clientes()              // relación definida en User
            ->pluck('nombre', 'id')   // id => nombre
            ->toArray();              // array plano para Filament
    }
}
