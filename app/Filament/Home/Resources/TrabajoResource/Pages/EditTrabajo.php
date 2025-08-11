<?php

namespace App\Filament\Home\Resources\TrabajoResource\Pages;

use App\Filament\Home\Resources\TrabajoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTrabajo extends EditRecord
{
    protected static string $resource = TrabajoResource::class;

    protected function getHeaderActions(): array
    {
        return [
           
        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
