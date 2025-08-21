<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pagoinsumo extends Model
{
     protected $fillable = [
        'fecha',
        'pago',
        'ordencompra_id',
    ];
      public function ordencompra()
    {
        return $this->belongsTo(Ordencompra::class);
    }
}
