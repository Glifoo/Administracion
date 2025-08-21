<?php

namespace App\Filament\Home\Resources\TrabajoResource\Pages;

use App\Filament\Home\Resources\TrabajoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;


class EditTrabajo extends EditRecord
{
    protected static string $resource = TrabajoResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
    public function mount($record): void
    {
        parent::mount($record);

        if ($this->record->estado === 'cotizado') {
            Notification::make()
                    ->title("¡Esta cotizacion no se puede modificar!")
                    ->danger()
                    ->persistent()
                    ->send();
            $this->redirect($this->getResource()::getUrl('index'));
        }
    }
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
