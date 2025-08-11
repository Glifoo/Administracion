<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class Suscripcion extends Model
{
    protected $fillable = [
        'user_id',
        'paquete_id',
        'fecha_inicio',
        'fecha_fin',
        'estado'
    ];

    /**
     * 
     * realciones
     */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paquete()
    {
        return $this->belongsTo(Paquete::class);
    }

    public function ventas()
    {
        return $this->hasMany(Sell::class);
    }

    public function renovations()
    {
        return $this->hasMany(Renovation::class);
    }


    /**
     * 
     * METODOS
     */

    public function renovar(int $meses)
    {
        $ahora = now();

        if ($this->fecha_fin && $ahora->lt(Carbon::parse($this->fecha_fin))) {
            // La suscripción aún está activa → solo extiendo la fecha_fin
            $this->fecha_fin = Carbon::parse($this->fecha_fin)->addMonths($meses);
            $this->estado = 1;
        } else {
            // La suscripción ha vencido → reinicio desde ahora
            $this->fecha_inicio = $ahora;
            $this->fecha_fin = $ahora->copy()->addMonths($meses);
            $this->estado = 1;
        }

        $this->save();
    }

    public function tieneSuscripcionActiva()
    {
        return optional($this->suscripcion)->estado === true;
    }

    public function setFechaFinAttribute($value)
    {
        if ($this->fecha_inicio && !$value) {
            $this->attributes['fecha_fin'] = Carbon::parse($this->fecha_inicio)
                ->addMonths(request()->input('meses_suscripcion', 1));
        } else {
            $this->attributes['fecha_fin'] = $value;
        }
    }

    public function diasRestantes()
    {
        $fechaFin = Carbon::parse($this->fecha_fin);
        $dias = (int) round(now()->diffInDays($fechaFin, false));

        switch (true) {
            case $dias < 0:
                return [
                    'dias' => $dias,
                    'texto' => "Vencido hace " . abs($dias) . " días",
                    'color' => 'danger'
                ];

            case $dias == 0:
                return [
                    'dias' => 0,
                    'texto' => 'Vence hoy',
                    'color' => 'warning'
                ];

            case $dias == 1:
                return [
                    'dias' => 1,
                    'texto' => 'Vence mañana',
                    'color' => 'warning'
                ];

            case $dias <= 7:
                return [
                    'dias' => $dias,
                    'texto' => "{$dias} días restantes",
                    'color' => 'warning'
                ];

            default:
                return [
                    'dias' => $dias,
                    'texto' => "{$dias} días restantes",
                    'color' => 'success'
                ];
        }
    }
    public function getDiasRestantesTextoAttribute()
    {
        return $this->diasRestantes()['texto'];
    }

    public function getDiasRestantesColorAttribute()
    {
        return $this->diasRestantes()['color'];
    }
}
