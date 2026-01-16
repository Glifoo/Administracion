<?php

namespace App\Filament\Home\Resources;

use App\Filament\Home\Resources\MovimientoahorroResource\Pages;
use App\Filament\Home\Resources\MovimientoahorroResource\RelationManagers;
use App\Models\Cuentahorro;
use App\Models\Movimientoahorro;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class MovimientoahorroResource extends Resource
{
    protected static ?string $model = Movimientoahorro::class;

    protected static ?string $navigationIcon = 'heroicon-s-document-currency-dollar';
    protected static ?string $navigationGroup = 'Cuentas personales';
    protected static ?string $navigationLabel = 'Transacciones';
    protected static ?string $modelLabel = 'Transacciones';
    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->whereHas('cuenta', function (Builder $query) {
            $query->where('user_id', Auth::id());
        });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Datos técnicos del Trabajos')
                    ->columns(3)
                    ->schema([
                        Select::make('cuenta_ahorro_id')
                            ->label('Seleccione la cuenta')
                            ->searchable()
                            ->helperText('Elija el nombre de uno de sus clientes.')
                            ->preload()
                            ->options(Cuentahorro::optionsForAuthUser())
                            ->required(),

                        Forms\Components\Select::make('tipo')
                            ->label('Tipo de movimiento')
                            ->options([
                                'deposito' => 'Depósito',
                                'retiro' => 'Retiro',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('monto')
                            ->label('Monto')
                            ->numeric()
                            ->step('0.01')
                            ->required(),

                        Forms\Components\TextInput::make('concepto')
                            ->label('Concepto')
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('fecha')
                            ->label('Fecha')
                            ->default(now())
                            ->required(),
                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                tables\Columns\TextColumn::make('tipo')
                    ->label('Cuenta'),

                tables\Columns\TextColumn::make('tipo')
                    ->label('tipo'),

                tables\Columns\TextColumn::make('monto')
                    ->label('Monto'),

                tables\Columns\TextColumn::make('concepto')
                    ->label('Concepto'),

                tables\Columns\TextColumn::make('fecha')
                    ->date('d/m/Y')
                    ->label('fecha'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListMovimientoahorros::route('/'),
            'create' => Pages\CreateMovimientoahorro::route('/create'),
            'edit' => Pages\EditMovimientoahorro::route('/{record}/edit'),
        ];
    }
}
