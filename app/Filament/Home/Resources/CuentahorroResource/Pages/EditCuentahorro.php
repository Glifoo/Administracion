<?php

namespace App\Filament\Home\Resources\CuentahorroResource\Pages;

use App\Filament\Home\Resources\CuentahorroResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCuentahorro extends EditRecord
{
    protected static string $resource = CuentahorroResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
