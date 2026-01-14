<?php

namespace App\Filament\Home\Resources\CuentahorroResource\Pages;

use App\Filament\Home\Resources\CuentahorroResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;
use Filament\Notifications\Notification;

class CreateCuentahorro extends CreateRecord
{
    protected static string $resource = CuentahorroResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = Auth::user();

        // 2) Encontrar la suscripción activa
        $suscripcion = $user->tieneSuscripcionActiva();
        if (! $suscripcion) {
            Notification::make()
                ->title('No tienes suscripción activa')
                ->danger()
                ->send();
            $this->halt(); 
        }

        $data['user_id'] = $user->id;

        return $data;
    }
}
