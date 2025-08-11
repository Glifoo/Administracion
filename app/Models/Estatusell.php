<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estatusell extends Model
{
    protected $fillable = [
        'nombre',
    ];

    /**
     * 
     * realciones
     */

    public function sells()
    {
        return $this->hasMany(Sell::class, 'estadov_id');
    }
}
