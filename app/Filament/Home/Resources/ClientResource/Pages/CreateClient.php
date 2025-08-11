<?php

namespace App\Filament\Home\Resources\ClientResource\Pages;

use App\Filament\Home\Resources\ClientResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClient extends CreateRecord
{
    protected static string $resource = ClientResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Reemplaza cualquier valor malicioso que venga del front
        $data['usuario_id'] = auth()->id();
        return $data;
    }
}
