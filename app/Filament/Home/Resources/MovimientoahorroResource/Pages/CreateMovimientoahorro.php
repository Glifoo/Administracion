<?php

namespace App\Filament\Home\Resources\MovimientoahorroResource\Pages;

use App\Filament\Home\Resources\MovimientoahorroResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMovimientoahorro extends CreateRecord
{
    protected static string $resource = MovimientoahorroResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    protected function afterCreate(): void
    {
        $movimiento = $this->record;
        $cuenta = $movimiento->cuenta;

        if ($movimiento->tipo === 'deposito') {
            $cuenta->increment('saldo', $movimiento->monto);
        }

        if ($movimiento->tipo === 'retiro') {
            if ($cuenta->saldo >= $movimiento->monto) {
                $cuenta->decrement('saldo', $movimiento->monto);
            } else {
                throw new \Exception('Saldo insuficiente en la cuenta de ahorro.');
            }
        }
    }
}
