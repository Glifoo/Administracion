<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Swindon\FilamentHashids\Traits\HasHashid;

class Cuentahorro extends Model
{
    use HasHashid;
    protected $fillable = [
        'user_id',
        'nombre',
        'saldo',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoAhorro::class, 'cuenta_ahorro_id');
    }

    public function depositar(float $monto, string $concepto = null): void
    {
        $this->movimientos()
            ->create([
                'tipo' => 'deposito',
                'monto' => $monto,
                'concepto' => $concepto,
                'fecha' => now(),
            ]);
        $this->increment('saldo', $monto);
    }

    public function retirar(float $monto, string $concepto = null): bool
    {
        if ($this->saldo < $monto) {
            return false;
        }
        $this->movimientos()
            ->create([
                'tipo' => 'retiro',
                'monto' => $monto,
                'concepto' => $concepto,
                'fecha' => now(),
            ]);
        $this->decrement('saldo', $monto);
        return true;
    }

    public function saldoActual(): float
    {
        return $this->saldo;
    }

    public static function optionsForAuthUser(): array
    {
        return Auth::user()
            ->cuentasAhorro()              // relación definida en User
            ->pluck('nombre', 'id')   // id => nombre
            ->toArray();              // array plano para Filament
    }
}
