<?php

namespace App\Filament\Home\Resources;

use App\Filament\Home\Resources\TrabajoResource\Pages;
use App\Filament\Home\Resources\TrabajoResource\Pages\CotizarTrabajo;
use App\Filament\Home\Resources\TrabajoResource\Pages\VerInsumo;
use App\Filament\Home\Resources\TrabajoResource\RelationManagers;
use App\Models\Client;
use App\Models\Trabajo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Filters\SelectFilter;

class TrabajoResource extends Resource
{
    protected static ?string $model = Trabajo::class;
    protected static ?string $navigationIcon = 'heroicon-c-wrench-screwdriver';
    protected static ?string $navigationGroup = 'Datos de Usuarios';
    protected static ?string $navigationLabel = 'Trabajos';
    protected static ?int $navigationSort = 2;

    public static function contarPorCotizar(): int
    {
        return self::where('estado', 'por cotizar')
            ->whereHas('cliente', function ($query) {
                $query->where('usuario_id', auth()->id());
            })
            ->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereHas('cliente', function ($query) {
                $query->where('usuario_id', auth()->id());
            });
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Datos técnicos del Trabajos')
                    ->columns(3)
                    ->schema([

                        Select::make('cliente_id')
                            ->label('Seleccione el cliente')
                            ->searchable()
                            ->helperText('Elija el nombre de uno de sus clientes.')
                            ->preload()
                            ->options(Client::optionsForAuthUser())
                            ->required(),

                        Forms\Components\TextInput::make('trabajo')
                            ->label('Nombre del trabajo')
                            ->helperText('Nombre del trabajo a realizar.')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('cantidad')
                            ->label('Cantidad')
                            ->required()
                            ->helperText('Cantidad del trabajo a realizar.')
                            ->numeric()
                            ->default(0.00),

                        Forms\Components\TextInput::make('ganancia')
                            ->label('Ganancia sobre el trabajo')
                            ->required()
                            ->helperText('Poner cual sera su ganancia sobre la produccion del trabajo 0 - 100%.')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0.00),

                        Forms\Components\TextInput::make('iva')
                            ->label('Porcentaje iva')
                            ->required()
                            ->helperText('Porcentaje a pagar a impuestos en caso de requerirlo 0 - 100%.')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->default(0.00),
                    ]),
                Section::make('Descripcion')
                    ->columns(1)
                    ->schema([
                        RichEditor::make('descripcion')
                            ->helperText('Descripcion completa del trabajo a realizar.')
                            ->required(),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                tables\Columns\TextColumn::make('cliente.nombre')
                    ->label('Cliente')
                    ->searchable(),

                tables\Columns\TextColumn::make('trabajo')
                    ->label('Trabajo')
                    ->searchable(),

                tables\Columns\TextColumn::make('cantidad')
                    ->numeric()
                    ->label('Cantidad'),

                tables\Columns\TextColumn::make('Costoproduccion')
                    ->label('Costo de producción')
                    ->searchable(),

                tables\Columns\TextColumn::make('gananciaefectivo')
                    ->label('Ganancia')
                    ->searchable(),

                tables\Columns\TextColumn::make('estado')
                    ->label('Estado')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'por cotizar' => 'warning',
                        'rechazado' => 'danger',
                        'cotizado' => 'success',
                    })
                    ->searchable(),

                tables\Columns\TextColumn::make('Costofinal')
                    ->label('Costo final')
                    ->searchable(),
            ])
            ->filters([
                SelectFilter::make('cliente')
                    ->relationship(
                        'cliente',
                        'nombre',
                        fn(Builder $query) => $query->where('usuario_id', auth()->id())
                    )
                    ->searchable()
                    ->preload(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color(fn(Trabajo $record): string => $record->estado === 'por cotizar' ? 'success' : 'gray')
                        ->disabled(fn(Trabajo $record): bool => $record->estado === 'cotizado'),

                    ViewAction::make()
                        ->color(fn(Trabajo $record): string => $record->estado === 'cotizado' ? 'success' : 'success'),

                    Tables\Actions\Action::make('Verinsumo')
                        ->label('Ver Insumos')
                        ->icon('heroicon-m-magnifying-glass-circle')
                        ->url(fn(Trabajo $record): string => route('filament.home.resources.trabajos.Verinsumo', ['record' => $record]))
                        ->color(fn(Trabajo $record): string => $record->estado === 'por cotizar' ? 'gray' : 'primary')
                        ->disabled(fn(Trabajo $record): bool => $record->estado === 'por cotizar'),

                    Tables\Actions\Action::make('cotizar')
                        ->label('Cotizar')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->url(fn(Trabajo $record): string => route('filament.home.resources.trabajos.cotizar', ['record' => $record]))
                        ->color(fn(Trabajo $record): string => $record->estado === 'por cotizar' ? 'success' : 'gray')
                        ->disabled(fn(Trabajo $record): bool => $record->estado === 'cotizado'),

                    Tables\Actions\DeleteAction::make()
                        ->color(fn(Trabajo $record): string => $record->estado === 'cotizado' ? 'gray' : 'danger'),
                    // ->disabled(fn(Trabajo $record): bool => $record->estado === 'cotizado'),
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
            'index' => Pages\ListTrabajos::route('/'),
            'create' => Pages\CreateTrabajo::route('/create'),
            'edit' => Pages\EditTrabajo::route('/{record}/edit'),
            'cotizar' => CotizarTrabajo::route('/{record}/cotizar'),
            'Verinsumo' => VerInsumo::route('/{record}/verinsumo'),
        ];
    }
}
