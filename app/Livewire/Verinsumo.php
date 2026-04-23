<?php

namespace App\Livewire;

use App\Models\Insumo;
use App\Models\Trabajo;
use Livewire\Component;

class Verinsumo extends Component
{
    public $identificador;
    public $trabajo;
    public $insumos;
    public $totalInsumos;

    public function mount()
    {
        $this->cargarDatos();
    }

    protected function cargarDatos()
    {
        // Una sola consulta optimizada
        $this->trabajo = Trabajo::with([
            'insumos' => function ($query) {
                $query->with('medida')  // Eager loading de medida
                    ->orderBy('nombre')
                    ->select('id', 'nombre', 'detalle', 'costo', 'cantidad', 'trabajo_id');
            },
            'cliente:id,nombre' // Si necesitas datos del cliente
        ])->findOrFail($this->identificador);

        $this->insumos = $this->trabajo->insumos;

        // Calcular totales si es necesario
        $this->totalInsumos = $this->insumos->sum(function ($insumo) {
            return $insumo->costo ;
        });
    }

    public function render()
    {
        return view('livewire.verinsumo', [
            'insumos' => $this->insumos,
            'trabajo' => $this->trabajo,
            'totalInsumos' => $this->totalInsumos ?? 0
        ]);
    }
}
