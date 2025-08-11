<?php

namespace App\Filament\Home\Resources\TrabajoResource\Pages;

use App\Filament\Home\Resources\TrabajoResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTrabajo extends CreateRecord
{
    protected static string $resource = TrabajoResource::class;
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
