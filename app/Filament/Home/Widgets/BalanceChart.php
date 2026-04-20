<?php

namespace App\Filament\Home\Widgets;

use App\Models\Movimientoahorro;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\BarChartWidget;

class BalanceChart extends BarChartWidget
{
    protected static ?string $heading = 'Balance mensual';
    protected static ?int $sort = 2;


    protected function getData(): array
    {
        $balances = Movimientoahorro::selectRaw("
                DATE_FORMAT(fecha, '%Y-%m') as mes,
                SUM(CASE WHEN tipo = 'deposito' THEN monto ELSE 0 END) as ingresos,
                SUM(CASE WHEN tipo = 'retiro' THEN monto ELSE 0 END) as egresos,
                SUM(CASE WHEN tipo = 'transferencia' THEN monto ELSE 0 END) as transferencias
            ")
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Ingresos',
                    'data' => $balances->map(fn($row) => $row->ingresos)->toArray(),
                    'backgroundColor' => '#22c55e', // verde
                ],
                [
                    'label' => 'Egresos',
                    'data' => $balances->map(fn($row) => $row->egresos)->toArray(),
                    'backgroundColor' => '#ef4444', // rojo
                ],
                [
                    'label' => 'Transferencias',
                    'data' => $balances->map(fn($row) => $row->transferencias)->toArray(),
                    'backgroundColor' => '#3b82f6', // azul
                ],
            ],
            'labels' => $balances->map(fn($row) => $row->mes)->toArray(),
        ];
    }

    protected function getFooter(): ?string
    {
        $balances = Movimientoahorro::selectRaw("
                SUM(CASE WHEN tipo = 'deposito' THEN monto ELSE 0 END) as ingresos,
                SUM(CASE WHEN tipo = 'retiro' THEN monto ELSE 0 END) as egresos
            ")
            ->first();

        $balance = $balances->ingresos - $balances->egresos;

        return "Balance total: " . number_format($balance, 2) . " Bs";
    }
}
