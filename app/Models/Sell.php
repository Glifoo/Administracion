<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class Sell extends Model
{
    protected $fillable = [
        'total',
        'pago',
        'fecha',
        'concepto',
        'suscripcion_id',
        'estadov_id'
    ];
    protected $casts = [
        'total' => 'decimal:2',
        'fecha' => 'datetime',
    ];
    /**
     * 
     * realciones
     */

    public function suscripcion()
    {
        return $this->belongsTo(Suscripcion::class, 'suscripcion_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estatusell::class, 'estadov_id');
    }
    /**
     * 
     * METODOS
     */
    public function procesarSuscripcion()
    {
        $this->update([
            'pago' => $this->total,
            'estadov_id' => 2,
        ]);

        $this->suscripcion?->update(['estado' => '1']);

        Notification::make()
            ->title('Suscripción activada')
            ->success()
            ->send();
    }
    public function procesarRenovacion()
    {
        $this->update([
            'pago' => $this->total,
            'estadov_id' => 2,
        ]);

        $renewal = $this->suscripcion?->renovations()
            ->where('estado', 'pendiente')
            ->latest()
            ->first();

        if ($renewal) {
            $this->suscripcion->renovar($renewal->meses);
            $renewal->update(['estado' => '2']);
        }

        Notification::make()
            ->title('Renovación completada')
            ->success()
            ->send();
    }
}
