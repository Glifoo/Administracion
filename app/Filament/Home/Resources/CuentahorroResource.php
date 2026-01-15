<?php

namespace App\Filament\Home\Resources;

use App\Filament\Home\Resources\CuentahorroResource\Pages;
use App\Filament\Home\Resources\CuentahorroResource\RelationManagers;
use App\Models\Cuentahorro;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;

class CuentahorroResource extends Resource
{
    protected static ?string $model = Cuentahorro::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationGroup = 'Cuentas personales';
    protected static ?string $navigationLabel = 'Cuenta personal';
    protected static ?string $modelLabel = 'Cuentas personales';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('user_id', Auth::user()->id);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Cuentas personales')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('nombre')
                            ->label('Nombre de cuenta')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('saldo')
                            ->label('Saldo')
                            ->required()
                            ->hiddenOn(['edit'])
                            ->helperText('Saldo de la cuenta.')
                            ->numeric()
                            ->default(0.00),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                tables\Columns\TextColumn::make('nombre')
                    ->label('Nombre'),
                tables\Columns\TextColumn::make('saldo')
                    ->numeric()
                    ->label('Saldo'),
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
            'index' => Pages\ListCuentahorros::route('/'),
            'create' => Pages\CreateCuentahorro::route('/create'),
            'edit' => Pages\EditCuentahorro::route('/{record}/edit'),
        ];
    }
}
