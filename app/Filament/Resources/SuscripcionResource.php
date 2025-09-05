<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SuscripcionResource\Pages;
use App\Filament\Resources\SuscripcionResource\RelationManagers;
use App\Models\Suscripcion;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;

class SuscripcionResource extends Resource
{
    protected static ?string $model = Suscripcion::class;

    protected static ?string $navigationLabel = 'Suscripciones';
    protected static ?string $navigationIcon = 'heroicon-m-pencil';
    protected static ?string $navigationGroup = 'Datos de Usuarios';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Select::make('user_id')
                    ->label('Usuario')
                    ->hiddenOn(['edit'])
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('paquete_id')
                    ->label('Paquete')
                    ->relationship('paquete', 'nombre')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\DatePicker::make('fecha_inicio')
                    ->default(now())
                    ->required()
                    ->readOnly()
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        if ($get('meses_suscripcion') && $get('fecha_inicio')) {
                            $fechaFin = Carbon::parse($get('fecha_inicio'))
                                ->addMonths($get('meses_suscripcion'));
                            $set('fecha_fin', $fechaFin);
                        }
                    }),

                Forms\Components\TextInput::make('meses_suscripcion')
                    ->label('Meses de suscripción')
                    ->numeric()
                    ->hiddenOn(['edit'])
                    ->minValue(1)
                    ->default(1)
                    ->live()
                    ->afterStateUpdated(function (Get $get, Set $set) {
                        if ($get('fecha_inicio')) {
                            $fechaFin = Carbon::parse($get('fecha_inicio'))
                                ->addMonths($get('meses_suscripcion'));
                            $set('fecha_fin', $fechaFin);
                        }
                    }),

                Forms\Components\DatePicker::make('fecha_fin')
                    ->label('Fecha de finalización')
                    ->readOnly()
                    ->required(),

                Forms\Components\Toggle::make('estado')
                    ->label('Estado Activo')
                    ->hiddenOn(['create'])
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('paquete.nombre')
                    ->numeric(),
                Tables\Columns\IconColumn::make('estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('fecha_inicio')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha_fin')
                    ->label('Finsuscripcion')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dias_restantes_texto')
                    ->label('Días restantes')
                    ->color(function ($record) {
                        return $record->dias_restantes_color;
                    }),

            ])
            ->filters([
                SelectFilter::make('estado')
                    ->label('Estado de la suscripción')
                    ->options([
                        true => 'Activo',
                        false => 'Inactivo',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value']) && $data['value'] !== '') {
                            $estado = filter_var($data['value'], FILTER_VALIDATE_BOOLEAN);
                            $query->where('estado', $estado);
                        }
                    })
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('success'),

                    ViewAction::make()
                        ->label('Ver Cliente')
                        ->color('primary'),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
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
            'index' => Pages\ListSuscripcions::route('/'),
            'edit' => Pages\EditSuscripcion::route('/{record}/edit'),
        ];
    }
}
