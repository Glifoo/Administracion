<?php

namespace App\Filament\Home\Resources;

use App\Filament\Home\Resources\OrdencompraResource\Pages;
use App\Filament\Home\Resources\OrdencompraResource\RelationManagers;
use App\Models\Ordencompra;
use App\Models\Trabajo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;


class OrdencompraResource extends Resource
{
    protected static ?string $model = Ordencompra::class;

    protected static ?string $navigationIcon = 'heroicon-s-document-currency-dollar';
    protected static ?string $navigationGroup = 'Pagos';
    protected static ?string $navigationLabel = 'Cuentas por pagar';
    protected static ?string $pluralModelLabel = 'Pagos por realizar';

    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('insumo.trabajo.cliente', function ($query) {
                $query->where('usuario_id', auth()->id());
            });
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                tables\Columns\TextColumn::make('insumo.trabajo.cliente.nombre')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Cliente'),

                tables\Columns\TextColumn::make('insumo.trabajo.trabajo')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Nombre trabajo'),

                tables\Columns\TextColumn::make('insumo.nombre')
                    ->label('Nombre insumo'),

                tables\Columns\TextColumn::make('insumo.costo')
                    ->numeric()
                    ->label('Costo'),

                tables\Columns\TextColumn::make('cuenta')
                    ->label('A cuenta'),

                tables\Columns\TextColumn::make('saldo')
                    ->label('Saldo'),

                tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Por pagar' => 'danger',
                        'cancelado' => 'success',
                    })
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('trabajo')
                    ->label('Filtrar por Trabajo')
                    ->options(function () {
                        // Obtener los trabajos del usuario autenticado
                        return Trabajo::whereHas('cliente', function ($query) {
                            $query->where('usuario_id', auth()->id());
                        })
                            ->pluck('trabajo', 'id') // Cambia 'trabajo' por el nombre real del campo
                            ->toArray();
                    })
                    ->searchable()
                    ->preload()
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->whereHas('insumo.trabajo', function ($subQuery) use ($data) {
                                $subQuery->where('id', $data['value']);
                            });
                        }
                    }),
                SelectFilter::make('cliente')
                    ->label('Filtrar por Cliente')
                    ->relationship(
                        'insumo.trabajo.cliente',
                        'nombre',
                        fn(Builder $query) => $query->where('usuario_id', auth()->id())
                    )
                    ->searchable()
                    ->preload(),
                    
                SelectFilter::make('estado')
                    ->label('Estado de Pago')
                    ->options([
                        'Por pagar' => 'Por pagar',
                        'Pagado' => 'Pagado',
                        // Agrega otros estados si los tienes
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->where('estado', $data['value']);
                        }
                    })
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('Pagar')
                        ->label('Pago')
                        ->icon('heroicon-o-clipboard-document-list')

                    // ->url(fn(Ordenpago $record): string => route('filament.home.resources.ordenpagos.pago', ['record' => $record]))


                    // ->color(fn(Ordenpago $record): string => $record->estado === 'Por pagar' ? 'success' : 'primary')
                    // ->disabled(fn(Ordenpago $record): bool => $record->estado === 'cotizado'),
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
            'index' => Pages\ListOrdencompras::route('/'),
        ];
    }
}
