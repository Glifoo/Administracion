<?php

namespace App\Filament\Home\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Client;
use App\Models\Trabajo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class cotizacionpormes extends ChartWidget
{
   protected static ?string $heading = 'Cotizaciones por Mes';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $userId = Auth::id();
        
        // Obtener IDs de clientes del usuario actual
        $clientesIds = Client::where('usuario_id', $userId)->pluck('id');
        
        // Obtener datos de los últimos 6 meses
        $data = [];
        $labels = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $startOfMonth = $month->copy()->startOfMonth();
            $endOfMonth = $month->copy()->endOfMonth();
            
            $count = Trabajo::whereIn('cliente_id', $clientesIds)
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->count();
            
            $data[] = $count;
            $labels[] = $month->translatedFormat('M Y');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cotizaciones',
                    'data' => $data,
                    'backgroundColor' => '#3B82F6',
                    'borderColor' => '#1D4ED8',
                    'borderWidth' => 1,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
