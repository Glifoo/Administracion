<?php

namespace App\Filament\Home\Resources\MovimientoahorroResource\Pages;

use App\Filament\Home\Resources\MovimientoahorroResource;
use App\Models\Cuentahorro;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateMovimientoahorro extends CreateRecord
{
    protected static string $resource = MovimientoahorroResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $cuenta = Cuentahorro::find($data['cuenta_ahorro_id']);

        if ($data['tipo'] === 'retiro' && $cuenta->saldo < $data['monto']) {
            Notification::make()
                ->title('Límite alcanzado')
                ->body("Saldo insuficiente en la cuenta de ahorro. Saldo actual: {$cuenta->saldo}")
                ->danger()
                ->persistent()
                ->send();

            $this->halt();
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $movimiento = $this->record;
        $cuenta = $movimiento->cuenta;

        if ($movimiento->tipo === 'deposito') {
            $cuenta->increment('saldo', $movimiento->monto);
        }

        if ($movimiento->tipo === 'retiro') {
            // Aquí ya sabemos que el saldo era suficiente
            $cuenta->decrement('saldo', $movimiento->monto);
        }
    }
}
