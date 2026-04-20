<?php

namespace App\Filament\Home\Widgets;

use App\Models\Cuentahorro;
use App\Models\Movimientoahorro;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\BarChartWidget;
use Illuminate\Support\Facades\Auth;

class BalanceChart extends BarChartWidget
{
    protected static ?string $heading = 'Balance mensual';
    protected static ?int $sort = 2;


    protected function getData(): array
    {
        $userId = Auth::id();

        // 🔎 Obtener las cuentas del usuario actual
        $cuentasIds = Cuentahorro::where('user_id', $userId)->pluck('id');

        $balances = Movimientoahorro::selectRaw("
                DATE_FORMAT(fecha, '%Y-%m') as mes,
                SUM(CASE WHEN tipo = 'deposito' THEN monto ELSE 0 END) as ingresos,
                SUM(CASE WHEN tipo = 'retiro' THEN monto ELSE 0 END) as egresos,
                SUM(CASE WHEN tipo = 'transferencia' THEN monto ELSE 0 END) as transferencias
            ")
            ->whereIn('cuenta_ahorro_id', $cuentasIds) // 🔎 Filtrar solo movimientos de las cuentas del usuario
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos',
                    'data' => $balances->map(fn($row) => $row->ingresos)->toArray(),
                    'backgroundColor' => '#22c55e',
                ],
                [
                    'label' => 'Egresos',
                    'data' => $balances->map(fn($row) => $row->egresos)->toArray(),
                    'backgroundColor' => '#ef4444',
                ],
                [
                    'label' => 'Transferencias',
                    'data' => $balances->map(fn($row) => $row->transferencias)->toArray(),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $balances->map(fn($row) => $row->mes)->toArray(),
        ];
    }

    protected function getFooter(): ?string
    {
        $userId = Auth::id();
        $cuentasIds = Cuentahorro::where('user_id', $userId)->pluck('id');

        $totales = Movimientoahorro::selectRaw("
                SUM(CASE WHEN tipo = 'deposito' THEN monto ELSE 0 END) as ingresos,
                SUM(CASE WHEN tipo = 'retiro' THEN monto ELSE 0 END) as egresos,
                SUM(CASE WHEN tipo = 'transferencia' THEN monto ELSE 0 END) as transferencias
            ")
            ->whereIn('cuenta_ahorro_id', $cuentasIds)
            ->first();

        $balance = $totales->ingresos - $totales->egresos;

        return "Ingresos: " . number_format($totales->ingresos, 2) . " Bs | 
                Egresos: " . number_format($totales->egresos, 2) . " Bs | 
                Transferencias: " . number_format($totales->transferencias, 2) . " Bs | 
                Balance neto: " . number_format($balance, 2) . " Bs";
    }
}
