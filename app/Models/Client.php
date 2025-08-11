<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Client extends Model
{
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
        return $this->hasMany(Trabajo::class);
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
