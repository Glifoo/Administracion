<?php

namespace App\Filament\Home\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Client;
use App\Models\Trabajo;
use Illuminate\Support\Facades\Auth;

class trabajoporcliente extends ChartWidget
{
     protected static ?string $heading = 'Trabajos por Cliente';
    protected static ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $userId = Auth::id();
        
        // Obtener top 10 clientes con más trabajos
        $clientesConTrabajos = Client::where('usuario_id', $userId)
            ->withCount('trabajos')
            ->has('trabajos', '>', 0)
            ->orderBy('trabajos_count', 'desc')
            ->limit(10)
            ->get();

        $data = [];
        $labels = [];
        $backgroundColors = [
            '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
            '#EC4899', '#06B6D4', '#84CC16', '#F97316', '#6366F1'
        ];

        foreach ($clientesConTrabajos as $index => $cliente) {
            $labels[] = $cliente->nombre . ' ' . $cliente->apellido;
            $data[] = $cliente->trabajos_count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Cantidad de Trabajos',
                    'data' => $data,
                    'backgroundColor' => array_slice($backgroundColors, 0, count($data)),
                    'borderColor' => '#1F2937',
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
