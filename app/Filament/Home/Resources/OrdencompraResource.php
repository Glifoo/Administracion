<?php

namespace App\Filament\Home\Resources;

use App\Filament\Home\Resources\OrdencompraResource\Pages;
use App\Filament\Home\Resources\OrdencompraResource\Pages\PagoInsumo;
use App\Filament\Home\Resources\OrdencompraResource\RelationManagers;
use App\Models\Client;
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
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;

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
                $query->where('usuario_id', Auth::user()->id);
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

                tables\Columns\TextColumn::make('saldo')
                    ->label('Saldo'),

                tables\Columns\TextColumn::make('estado')
                    ->label('Estado de pagos')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Por pagar' => 'danger',
                        'cancelado' => 'success',
                    })
                    ->searchable(),
            ])
            ->filters([
                Filter::make('cliente_trabajo')
                    ->form([
                        Select::make('cliente_id')
                            ->label('Cliente')
                            ->options(function () {
                                return Client::where('usuario_id', Auth::user()->id)
                                    ->pluck('nombre', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->live(),

                        Select::make('trabajo_id')
                            ->label('Trabajo')
                            ->options(function (Get $get) {
                                $clienteId = $get('cliente_id');
                                if (!$clienteId) {
                                    return [];
                                }
                                return \App\Models\Trabajo::query()
                                    ->where('cliente_id', $clienteId)
                                    ->orderBy('trabajo')
                                    ->pluck('trabajo', 'id');
                            })
                            ->searchable()
                            ->preload()
                            ->disabled(fn(Get $get) => ! $get('cliente_id')),
                    ])
                    ->query(function (Builder $query, array $data) {
                        return $query
                            ->when($data['cliente_id'], function ($query, $clienteId) {
                                $query->whereHas('insumo.trabajo', fn($q) => $q->where('cliente_id', $clienteId));
                            })
                            ->when($data['trabajo_id'], function ($query, $trabajoId) {
                                $query->whereHas('insumo', fn($q) => $q->where('trabajo_id', $trabajoId));
                            });
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['cliente_id'] ?? null) {
                            $indicators[] = 'Cliente: ' . Client::find($data['cliente_id'])?->nombre;
                        }
                        if ($data['trabajo_id'] ?? null) {
                            $indicators[] = 'Trabajo: ' . \App\Models\Trabajo::find($data['trabajo_id'])?->trabajo;
                        }
                        return $indicators;
                    }),

                SelectFilter::make('estado')
                    ->label('Estado de Pago')
                    ->options([
                        'Por pagar' => 'Por pagar',
                        'cancelado' => 'cancelado',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!empty($data['value'])) {
                            $query->where('estado', $data['value']);
                        }
                    })
            ])
            ->persistFiltersInSession()

            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('Pagar')
                        ->label('Pago insumo')
                        ->icon('heroicon-o-clipboard-document-list')

                        ->url(fn(Ordencompra $record): string => route('filament.home.resources.ordencompras.pago', ['record' => $record]))


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
            'pago' => PagoInsumo::route('/{record}/pago'),
        ];
    }
}
