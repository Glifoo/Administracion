<?php

namespace App\Filament\Home\Widgets;

use App\Models\Client;
use App\Models\Cuentahorro;
use App\Models\Ordenpago;
use App\Models\Suscripcion;
use App\Models\Trabajo;
use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;


class WidgetStats extends BaseWidget
{
    protected function getStats(): array
    {
        $userId = Auth::id();

        $suscripcion = Suscripcion::where('user_id', $userId)->first();

        $tiempoRestante = null;

        if ($suscripcion && $suscripcion->fecha_fin) {
            $hoy = Carbon::now();
            $fin = Carbon::parse($suscripcion->fecha_fin);

            if ($fin->isPast()) {
                $tiempoRestante = 'Expirada';
                $descripcionTiempo = 'La suscripción ya terminó';
            } else {
                $diff = $hoy->diff($fin);
                $mesesRestantes = $diff->m + ($diff->y * 12);
                $diasRestantes = $diff->d;
                $tiempoRestante = "{$mesesRestantes} mes(es) y {$diasRestantes} día(s)";
                $descripcionTiempo = "Restan {$mesesRestantes} mes(es) y {$diasRestantes} día(s) de suscripción";
            }
        }

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
        $totalCuentas = Cuentahorro::where('user_id', $userId)->count();

        $saldoTotal = number_format(
            Cuentahorro::where('user_id', $userId)->sum('saldo'),
            2
        );
        return [
            Stat::make('Tiempo de suscripción', $tiempoRestante ?? 'Sin datos')
                ->description($descripcionTiempo ?? '')
                ->descriptionIcon('heroicon-m-clock')
                ->icon('heroicon-o-calendar')
                ->color(($tiempoRestante ?? '') === 'Expirada' ? 'danger' : 'success'),

            Stat::make('Total Cotizaciones', $totalcotis)
                ->icon('heroicon-o-document-text')
                ->color('success'),

            Stat::make('Cotizaciones en Proceso', $enproceso)
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

            Stat::make('Tottal en cuentas', $saldoTotal)
                ->icon('heroicon-o-banknotes')
                ->color('info'),
        ];
    }
}
