<?php

namespace App\Filament\Home\Resources;

use App\Filament\Home\Resources\ClientResource\Pages;
use App\Filament\Home\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;
    protected static ?string $navigationIcon = 'heroicon-s-users';
    protected static ?string $navigationGroup = 'Datos de Usuarios';
    protected static ?string $navigationLabel = 'Clientes';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('usuario_id', auth()->id());
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Clientes')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('apellido')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('contacto')
                            ->tel()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nit')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->maxLength(255),


                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->label('Nombre'),

                tables\Columns\TextColumn::make('contacto')
                    ->label('Contacto'),

                tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->label('email'),

                tables\Columns\TextColumn::make('nit')
                    ->label('Nit'),

                tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de creacíon')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault:true),

            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClients::route('/'),
            'create' => Pages\CreateClient::route('/create'),
            'edit' => Pages\EditClient::route('/{record}/edit'),
        ];
    }
}
