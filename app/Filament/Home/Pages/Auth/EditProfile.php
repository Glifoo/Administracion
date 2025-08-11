<?php

namespace App\Filament\Home\Pages\Auth;

use App\Filament\Home\Resources\HomeResource;
use Filament\Forms\Components\Component;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;

class EditProfile extends BaseEditProfile
{

    protected static string $layout = 'filament-panels::components.layout.index';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Datos de Usuario')
                    ->columns(2)
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getLastNameFormComponent(),
                        $this->getPhoneNumberFormComponent(),
                        $this->getEmailFormComponent()
                            ->readOnly(),
                        $this->getLogoFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ]),

            ]);
    }
    protected function getLastNameFormComponent(): Component
    {
        return TextInput::make('lastname')
            ->label(__('Apellido'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }
    protected function getPhoneNumberFormComponent(): Component
    {
        return TextInput::make('phone')
            ->label(__('Celular'))
            ->tel()
            ->required()
            ->autofocus();
    }
    protected function getlogoFormComponent(): Component
    {
        return FileUpload::make('logo')
            ->label(__('Logo de perfil'))
            ->image()
            ->directory('profile-photos') // Directorio donde se guardarán las imágenes
            ->visibility('public') // O 'private' según tus necesidades
            ->imageEditor() // Habilita el editor de imágenes
            ->imageResizeTargetWidth(200) // Ancho máximo
            ->imageResizeTargetHeight(200) // Alto máximo
            ->maxSize(2048) // Tamaño máximo en KB (2MB)
            ->avatar() // Estilo de avatar circular
            ->columnSpanFull(); // Ocupa todo el ancho
    }
    protected function getRedirectUrl(): string
    {
        return '/home';
    }
}
