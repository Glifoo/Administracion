<?php

namespace App\Filament\Home\Widgets;

use App\Models\Client;
use App\Models\Ordenpago;
use App\Models\Trabajo;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;


class WidgetStats extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = Auth::id();

        $clientesIds = Client::where('usuario_id', $userId)
            ->pluck('id');
        $totalcotis = Trabajo::whereIn('cliente_id', $clientesIds)->count();

        $enproceso = Trabajo::whereIn('cliente_id', $clientesIds)
            ->where('estado', 'por cotizar')
            ->count();

        $gananciastotales = number_format(
            Trabajo::whereIn('cliente_id', $clientesIds)->sum('gananciaefectivo'),
            2
        );

        $ordenpagos = number_format(
            Ordenpago::whereIn('trabajo_id', function ($query) use ($clientesIds) {
                $query->select('id')
                    ->from('trabajos')
                    ->whereIn('cliente_id', $clientesIds);
            })->sum('saldo'),
            2
        );

        return [
            Stat::make('Total Cotizaciones', $totalcotis)

                ->icon('heroicon-o-document-text')
                ->color('success'),

            Stat::make('En Proceso', $enproceso)
                ->icon('heroicon-o-clock')
                ->color('warning')
                ->url(route('filament.home.resources.trabajos.index')),

            Stat::make('Ganancias Totales', $gananciastotales)
                ->description($gananciastotales . ' cobrado')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->icon('heroicon-o-currency-dollar')
                ->color('success'),

            Stat::make('Por cobrar', $ordenpagos)
                ->description($ordenpagos . ' por cobrar')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->icon('heroicon-o-exclamation-circle')
                ->color('danger')
                ->url(route('filament.home.resources.ordenpagos.index')),

        ];
    }
}
