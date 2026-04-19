<?php

namespace App\Livewire;

use App\Models\Cuentahorro;
use App\Models\CuentaTrabajo;
use App\Models\Insumo;
use App\Models\Ordencompra;
use App\Models\Ordenpago;
use App\Models\Trabajo;
use Livewire\Component;
use Filament\Notifications\Notification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

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
        $usuarioid = Auth::user()->id;

        $costoprod = $insumos->sum('costo');
        // Sumamos insumos + mano de obra para tener el costo base total
        $costoBaseTotal = $costoprod + $trabajo->manobra;

        // 1. Calcular el Precio Neto (Costo + Ganancia Deseada)
        // Usamos división para que el margen sea sobre el precio de venta neto
        $porcentajeGanancia = $trabajo->ganancia / 100;
        $precioNeto = ($porcentajeGanancia < 1) ? ($costoBaseTotal / (1 - $porcentajeGanancia)) : ($costoBaseTotal * (1 + $porcentajeGanancia));

        // 2. Calcular el Precio Final de Factura (Incluyendo Impuestos de Bolivia)
        if ($trabajo->iva > 0) {
            $porcentajeImpuesto = $trabajo->iva / 100;
            // Fórmula de Tasa Efectiva: Dividir entre (1 - tasa) para que el impuesto 
            // calculado sobre el total sea exacto.
            $total = $precioNeto / (1 - $porcentajeImpuesto);
            $ivaefec = $total * $porcentajeImpuesto; // Esto es lo que pagarás al estado
        } else {
            $total = $precioNeto;
            $ivaefec = 0;
        }

        // 3. Ganancia Real (Lo que queda tras pagar costos e impuestos del total)
        $gananciaefec = $total - $costoBaseTotal - $ivaefec;

        // Actualización del modelo
        $trabajo->update([
            'estado' => 'cotizado',
            'gananciaefectivo' => $gananciaefec,
            'ivaefectivo' => $ivaefec,
            'Costofactura' => $total, // Asegúrate de tener este campo en fillable si lo usas
            'Costoproduccion' => $costoBaseTotal,
            'Costofinal' => $total,
        ]);

        $trabajo->save();

        $cuenta = null;

        if ($trabajo->cuenta) {
            $cuenta = Cuentahorro::create([
                'nombre'   => 'Cuenta - ' . $trabajo->trabajo,
                'user_id'  => $usuarioid,
                'saldo'    => 0,
            ]);

            CuentaTrabajo::create([
                'cuenta_id'  => $cuenta->id,
                'trabajo_id' => $trabajo->id,
            ]);
        }

        $ordenPago = OrdenPago::create([
            'trabajo_id' => $trabajo->id,
            'total' => $total,
            'saldo' => $total,
        ]);

        foreach ($insumos as $insumo) {
            Ordencompra::create([
                'insumo_id' => $insumo->id,
                'total' => $insumo->costo,  // cada insumo como una orden individual
                'cuenta' => 0,              // aún no pagado
                'saldo' => $insumo->costo,
            ]);
        }


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
        $idtrabajo = $trabajo->id;

        // 1. Costo Base (Insumos + Mano de Obra)
        $costoprod = $insumos->sum('costo') + $trabajo->manobra;

        // 2. Cálculo del Precio Neto (Ganancia sobre venta)
        $porcentajeGanancia = $trabajo->ganancia / 100;
        // Evitamos división por cero si la ganancia es 100%
        $divisorGanancia = (1 - $porcentajeGanancia) > 0 ? (1 - $porcentajeGanancia) : 0.01;
        $precioNeto = $costoprod / $divisorGanancia;

        // 3. Cálculo del Total con Impuestos (Tasa Efectiva)
        if ($trabajo->iva > 0) {
            $porcentajeImpuesto = $trabajo->iva / 100;
            $divisorImpuesto = (1 - $porcentajeImpuesto) > 0 ? (1 - $porcentajeImpuesto) : 0.01;
            $total = $precioNeto / $divisorImpuesto;
            $ivaefec = $total * $porcentajeImpuesto;
        } else {
            $total = $precioNeto;
            $ivaefec = 0;
        }

        // 4. Ganancia Real Final (Lo que queda en bolsillo)
        $gananciafinal = $total - $costoprod - $ivaefec;

        return view('livewire.cotizar', [
            'insumos' => $insumos,
            'total' => $total,
            'costoprod' => $costoprod,
            'idtrabajo' => $idtrabajo,
            'ivaefec' => $ivaefec,
            'gananciafinal' => $gananciafinal
        ]);
    }
}
