<?php

namespace App\Filament\Home\Resources;

use App\Filament\Home\Resources\OrdenpagoResource\Pages;
use App\Filament\Home\Resources\OrdenpagoResource\Pages\Pagos;
use App\Filament\Home\Resources\OrdenpagoResource\RelationManagers;
use App\Filament\Home\Resources\TrabajoResource\Pages\CotizarTrabajo;
use App\Models\Ordenpago;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Blade;
use Filament\Tables\Actions\Action;

class OrdenpagoResource extends Resource
{
    protected static ?string $model = Ordenpago::class;

    protected static ?string $navigationIcon = 'heroicon-m-currency-dollar';
    protected static ?string $navigationGroup = 'Pagos';
    protected static ?string $navigationLabel = 'Cuentas por cobrar';
    protected static ?string $pluralModelLabel = 'Ventas y pagos';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('trabajo.cliente', function ($query) {
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
                tables\Columns\TextColumn::make('id')
                    ->label('ID'),

                tables\Columns\TextColumn::make('trabajo.cliente.nombre')
                    ->label('Nombre'),

                tables\Columns\TextColumn::make('trabajo.trabajo')
                    ->label('Nombre trabajo'),

                tables\Columns\TextColumn::make('trabajo.Costofinal')
                    ->numeric()
                    ->label('Total'),

                tables\Columns\TextColumn::make('saldo')
                    ->numeric()
                    ->label('Saldo'),

                tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Por pagar' => 'danger',
                        'cancelado' => 'success',
                    })
                    ->searchable(),

                tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha'),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\Action::make('Pagar')
                        ->label('Pago')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->url(fn(Ordenpago $record): string => route('filament.home.resources.ordenpagos.pago', ['record' => $record]))
                        ->color(fn(Ordenpago $record): string => $record->estado === 'Por pagar' ? 'success' : 'primary')
                        ->disabled(fn(Ordenpago $record): bool => $record->estado === 'cotizado'),

                    Tables\Actions\Action::make('pdf')
                        ->label('PDF')
                        ->color('success')
                        ->icon('heroicon-m-document-arrow-down')
                        ->url(fn (Ordenpago $record) => route('ordenpago.pdf', $record))
                        ->openUrlInNewTab()
                        ->color(fn(Ordenpago $record): string => $record->estado === 'Por pagar' ? 'gray' : 'success')
                        ->disabled(fn(Ordenpago $record): bool => $record->estado === 'Por pagar'),

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
            'index' => Pages\ListOrdenpagos::route('/'),
            'pago' => Pagos::route('/{record}/pago'),
        ];
    }
}
