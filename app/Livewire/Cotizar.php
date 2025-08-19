<?php

namespace App\Livewire;

use App\Models\Insumo;
use App\Models\Ordenpago;
use App\Models\Trabajo;
use Livewire\Component;
use Filament\Notifications\Notification;

class Cotizar extends Component
{
    public $identificador;
    public $trabajo;
    public $trabajoid;
    public $manobra;
    public $estadoActual;

    public $insumoAEliminar = null;
    public $editandoInsumoId = null;
    public $confirmandoFinalizacion = false;


    public function updated($propertyName)
    {
        $this->resetValidation($propertyName);
    }

    public function mount($identificador)
    {
        $this->identificador = $identificador;
        $this->loadTrabajoData();
    }
    public $nuevoInsumo = [
        'nombre' => '',
        'cantidad' => 1,
        'costo' => 0,
        'detalle' => ''
    ];


    public $insumoEditado = [
        'nombre' => '',
        'cantidad' => 1,
        'costo' => 0,
        'detalle' => ''
    ];


    protected function loadTrabajoData()
    {
        $trabajo = Trabajo::find($this->identificador);
        $this->trabajo = $trabajo->trabajo;
        $this->trabajoid = $trabajo->id;
        $this->manobra = $trabajo->manobra;
    }
    public function agregarInsumo()
    {
        $this->validate([
            'nuevoInsumo.nombre' => 'required|string|max:255',
            'nuevoInsumo.cantidad' => 'required|numeric|min:1',
            'nuevoInsumo.costo' => 'required|numeric|min:0',
        ]);

        Insumo::create([
            'trabajo_id' => $this->identificador,
            'nombre' => $this->nuevoInsumo['nombre'],
            'cantidad' => $this->nuevoInsumo['cantidad'],
            'costo' => $this->nuevoInsumo['costo'],
            'detalle' => $this->nuevoInsumo['detalle'],
        ]);

        $this->reset('nuevoInsumo');
        $this->dispatch('insumo-agregado');
    }

    public function editarInsumo($insumoId)
    {
        $this->editandoInsumoId = $insumoId;
        $insumo = Insumo::find($insumoId);
        $this->insumoEditado = [
            'nombre' => $insumo->nombre,
            'cantidad' => $insumo->cantidad,
            'costo' => $insumo->costo,
            'detalle' => $insumo->detalle
        ];
    }

    public function actualizarInsumo()
    {
        $this->validate([
            'insumoEditado.nombre' => 'required|string|max:255',
            'insumoEditado.cantidad' => 'required|numeric|min:1',
            'insumoEditado.costo' => 'required|numeric|min:0',
        ]);

        Insumo::find($this->editandoInsumoId)->update([
            'nombre' => $this->insumoEditado['nombre'],
            'cantidad' => $this->insumoEditado['cantidad'],
            'costo' => $this->insumoEditado['costo'],
            'detalle' => $this->insumoEditado['detalle'],
        ]);

        $this->cancelarEdicion();
    }

    public function cancelarEdicion()
    {
        $this->editandoInsumoId = null;
        $this->reset('insumoEditado');
    }

    public function eliminarInsumo()
    {
        if ($this->insumoAEliminar) {
            $insumo = Insumo::find($this->insumoAEliminar);

            if ($insumo) {
                $insumo->delete();

                Notification::make()
                    ->title('Insumo eliminado')
                    ->success()
                    ->send();
            }

            $this->insumoAEliminar = null;
        }
    }

    public function confirmarEliminacion($insumoId)
    {
        $this->insumoAEliminar = $insumoId;
        $this->dispatch('open-modal', id: 'confirmar-eliminacion');
    }

    public function confirmarFinalizacion()
    {
        $this->confirmandoFinalizacion = true;
        $this->dispatch('open-modal', id: 'confirmar-finalizacion');
    }

    public function terminarCotizacion()
    {
        $insumos = Insumo::where('trabajo_id', $this->identificador)->get();
        $trabajo = Trabajo::find($this->identificador);

        $costoprod = $insumos->sum('costo');
        $parcial = $costoprod + $trabajo->manobra;

        $ganancia = $parcial * $trabajo->ganancia / 100;
        $totalconganancia = $costoprod + $ganancia;

        if ($trabajo->iva > 0) {
            $iva = $totalconganancia * $trabajo->iva / 100;
            $total = $totalconganancia   + $iva;
        } else {
            $total = $totalconganancia + $ganancia;
            $iva=0;
        }

        if ($iva > 0) {
            $trabajo->update([
                'estado' => 'cotizado',
                'gananciaefectivo' => $ganancia,
                'ivaefectivo' => $iva ?? 0,
                'Costofactura' => $total,
                'Costoproduccion' => $costoprod,
                'Costofinal' => $total,
            ]);
        } else {
            $trabajo->update([
                'estado' => 'cotizado',
                'gananciaefectivo' => $ganancia,
                'ivaefectivo' => $iva ?? 0,
                'Costoproduccion' => $costoprod,
                'Costofinal' => $total,
            ]);
        }
        $trabajo->save();

        $ordenPago = OrdenPago::create([
            'trabajo_id' => $trabajo->id,
            'total' => $total,
            'saldo' => $total,
        ]);
        

        Notification::make()
            ->title('Cotización finalizada')
            ->body('Este trabajo ya no puede ser cotizado nuevamente.')
            ->success()
            ->send();

        return redirect()->route('filament.home.resources.trabajos.index');
    }

    public function render()
    {
        $insumos = Insumo::where('trabajo_id', $this->identificador)->get();
        $trabajo = Trabajo::find($this->identificador);

        $costoprod = $insumos->sum('costo');
        $parcial = $costoprod + $trabajo->manobra;

        $ganancia = $parcial * $trabajo->ganancia / 100;
        $totalconganancia = $costoprod + $ganancia;

        if ($trabajo->iva > 0) {
            $iva = $totalconganancia * $trabajo->iva / 100;
            $total = $totalconganancia   + $iva;
        } else {
            $total = $totalconganancia + $ganancia;
        }
        return view('livewire.cotizar', compact('insumos', 'total', 'costoprod'));
    }
}
