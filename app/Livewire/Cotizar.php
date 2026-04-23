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
use Illuminate\Support\Facades\DB;

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

    // Variables cacheadas para evitar recálculos
    protected $cachedInsumos = null;
    protected $cachedTrabajo = null;
    protected $cachedCalculos = null;

    public function updated($propertyName)
    {
        $this->resetValidation($propertyName);
        
        // Limpiar caché cuando se modifican datos relevantes
        if (str_starts_with($propertyName, 'nuevoInsumo') || 
            $propertyName === 'editandoInsumoId' ||
            $propertyName === 'insumoAEliminar') {
            $this->clearCache();
        }
    }

    public function mount($identificador)
    {
        $this->identificador = $identificador;
        $this->loadTrabajoData();
    }

    protected function clearCache()
    {
        $this->cachedInsumos = null;
        $this->cachedTrabajo = null;
        $this->cachedCalculos = null;
    }

    protected function loadTrabajoData()
    {
        // Optimización: Cargar solo los campos necesarios
        $trabajo = Trabajo::select('id', 'trabajo', 'manobra', 'ganancia', 'iva', 'estado')
            ->find($this->identificador);
            
        if (!$trabajo) {
            abort(404, 'Trabajo no encontrado');
        }
        
        $this->trabajo = $trabajo->trabajo;
        $this->trabajoid = $trabajo->id;
        $this->manobra = $trabajo->manobra;
        $this->estadoActual = $trabajo->estado;
        
        $this->cachedTrabajo = $trabajo;
    }

    protected function getInsumosOptimized()
    {
        if ($this->cachedInsumos === null) {
            $this->cachedInsumos = Insumo::where('trabajo_id', $this->identificador)
                ->select('id', 'nombre', 'cantidad', 'costo', 'detalle', 'trabajo_id')
                ->orderBy('created_at', 'desc')
                ->get();
        }
        return $this->cachedInsumos;
    }

    protected function getTrabajoOptimized()
    {
        if ($this->cachedTrabajo === null) {
            $this->cachedTrabajo = Trabajo::select('id', 'trabajo', 'manobra', 'ganancia', 'iva', 'estado')
                ->find($this->identificador);
        }
        return $this->cachedTrabajo;
    }

    public function agregarInsumo()
    {
        $this->validate([
            'nuevoInsumo.nombre' => 'required|string|max:255',
            'nuevoInsumo.cantidad' => 'required|numeric|min:1',
            'nuevoInsumo.costo' => 'required|numeric|min:0',
        ]);

        // Usar transacción para mantener integridad
        DB::transaction(function () {
            Insumo::create([
                'trabajo_id' => $this->identificador,
                'nombre' => $this->nuevoInsumo['nombre'],
                'cantidad' => $this->nuevoInsumo['cantidad'],
                'costo' => $this->nuevoInsumo['costo'],
                'detalle' => $this->nuevoInsumo['detalle'] ?? null,
            ]);
        });

        $this->reset('nuevoInsumo');
        $this->clearCache(); // Limpiar caché después de agregar
        
        Notification::make()
            ->title('Insumo agregado')
            ->success()
            ->send();
            
        $this->dispatch('insumo-agregado');
    }

    public function editarInsumo($insumoId)
    {
        $this->editandoInsumoId = $insumoId;
        
        // Optimización: Usar find con select específico
        $insumo = Insumo::select('id', 'nombre', 'cantidad', 'costo', 'detalle')
            ->find($insumoId);
            
        if ($insumo) {
            $this->insumoEditado = [
                'nombre' => $insumo->nombre,
                'cantidad' => $insumo->cantidad,
                'costo' => $insumo->costo,
                'detalle' => $insumo->detalle
            ];
        }
    }

    public function actualizarInsumo()
    {
        $this->validate([
            'insumoEditado.nombre' => 'required|string|max:255',
            'insumoEditado.cantidad' => 'required|numeric|min:1',
            'insumoEditado.costo' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () {
            Insumo::where('id', $this->editandoInsumoId)
                ->where('trabajo_id', $this->identificador) // Seguridad adicional
                ->update([
                    'nombre' => $this->insumoEditado['nombre'],
                    'cantidad' => $this->insumoEditado['cantidad'],
                    'costo' => $this->insumoEditado['costo'],
                    'detalle' => $this->insumoEditado['detalle'],
                ]);
        });

        $this->cancelarEdicion();
        $this->clearCache(); // Limpiar caché después de actualizar
        
        Notification::make()
            ->title('Insumo actualizado')
            ->success()
            ->send();
    }

    public function cancelarEdicion()
    {
        $this->editandoInsumoId = null;
        $this->reset('insumoEditado');
    }

    public function eliminarInsumo()
    {
        if ($this->insumoAEliminar) {
            DB::transaction(function () {
                $insumo = Insumo::where('id', $this->insumoAEliminar)
                    ->where('trabajo_id', $this->identificador)
                    ->first();
                    
                if ($insumo) {
                    $insumo->delete();
                }
            });

            $this->clearCache(); // Limpiar caché después de eliminar
            
            Notification::make()
                ->title('Insumo eliminado')
                ->success()
                ->send();
        }

        $this->insumoAEliminar = null;
    }

    public function confirmarEliminacion($insumoId)
    {
        $this->insumoAEliminar = $insumoId;
        $this->dispatch('open-modal', id: 'confirmar-eliminacion');
    }

    public function confirmarFinalizacion()
    {
        // Verificar si ya está cotizado
        $trabajo = $this->getTrabajoOptimized();
        if ($trabajo->estado === 'cotizado') {
            Notification::make()
                ->title('Error')
                ->body('Este trabajo ya ha sido cotizado anteriormente.')
                ->danger()
                ->send();
            return;
        }
        
        $this->confirmandoFinalizacion = true;
        $this->dispatch('open-modal', id: 'confirmar-finalizacion');
    }

    protected function calcularCostosOptimized($insumos, $trabajo)
    {
        // Optimización: Calcular usando colecciones
        $costoInsumos = $insumos->sum('costo');
        $costoBaseTotal = $costoInsumos + $trabajo->manobra;

        $porcentajeGanancia = $trabajo->ganancia / 100;
        $precioNeto = $porcentajeGanancia < 1 
            ? ($costoBaseTotal / (1 - $porcentajeGanancia)) 
            : ($costoBaseTotal * (1 + $porcentajeGanancia));

        if ($trabajo->iva > 0) {
            $porcentajeImpuesto = $trabajo->iva / 100;
            $total = $precioNeto / (1 - $porcentajeImpuesto);
            $ivaefec = $total * $porcentajeImpuesto;
        } else {
            $total = $precioNeto;
            $ivaefec = 0;
        }

        $gananciaefec = $total - $costoBaseTotal - $ivaefec;

        return [
            'costoBaseTotal' => $costoBaseTotal,
            'precioNeto' => $precioNeto,
            'total' => $total,
            'ivaefec' => $ivaefec,
            'gananciaefec' => $gananciaefec
        ];
    }

    public function terminarCotizacion()
    {
        // Verificar si ya está cotizado
        $trabajo = Trabajo::find($this->identificador);
        if ($trabajo->estado === 'cotizado') {
            Notification::make()
                ->title('Error')
                ->body('Este trabajo ya ha sido cotizado anteriormente.')
                ->danger()
                ->send();
            return redirect()->route('filament.home.resources.trabajos.index');
        }

        DB::transaction(function () {
            $insumos = Insumo::where('trabajo_id', $this->identificador)->get();
            $trabajo = Trabajo::lockForUpdate()->find($this->identificador); // Lock para evitar condiciones de carrera
            $usuarioid = Auth::id();

            $calculos = $this->calcularCostosOptimized($insumos, $trabajo);

            // Actualización optimizada del trabajo
            $trabajo->update([
                'estado' => 'cotizado',
                'gananciaefectivo' => $calculos['gananciaefec'],
                'ivaefectivo' => $calculos['ivaefec'],
                'Costofactura' => $calculos['total'],
                'Costoproduccion' => $calculos['costoBaseTotal'],
                'Costofinal' => $calculos['total'],
            ]);

            // Crear cuenta de ahorro si no existe
            if (!$trabajo->cuenta) {
                $cuenta = Cuentahorro::create([
                    'nombre' => 'Cuenta - ' . $trabajo->trabajo,
                    'user_id' => $usuarioid,
                    'saldo' => 0,
                ]);

                CuentaTrabajo::create([
                    'cuenta_id' => $cuenta->id,
                    'trabajo_id' => $trabajo->id,
                ]);
            }

            // Crear orden de pago
            $ordenPago = OrdenPago::create([
                'trabajo_id' => $trabajo->id,
                'total' => $calculos['total'],
                'saldo' => $calculos['total'],
            ]);

            // Crear órdenes de compra usando chunk para muchos insumos
            $insumos->chunk(100)->each(function ($chunk) use ($trabajo) {
                $ordenesCompra = [];
                foreach ($chunk as $insumo) {
                    $ordenesCompra[] = [
                        'insumo_id' => $insumo->id,
                        'total' => $insumo->costo,
                        'cuenta' => 0,
                        'saldo' => $insumo->costo,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                OrdenCompra::insert($ordenesCompra); // Insert masivo
            });
        });

        Notification::make()
            ->title('Cotización finalizada')
            ->body('Este trabajo ya no puede ser cotizado nuevamente.')
            ->success()
            ->send();

        return redirect()->route('filament.home.resources.trabajos.index');
    }

    public function render()
    {
        // Obtener datos optimizados
        $insumos = $this->getInsumosOptimized();
        $trabajo = $this->getTrabajoOptimized();
        
        // Verificar si el trabajo existe
        if (!$trabajo) {
            abort(404, 'Trabajo no encontrado');
        }
        
        $idtrabajo = $trabajo->id;
        
        // Calcular costos usando el método optimizado
        $calculos = $this->calcularCostosOptimized($insumos, $trabajo);
        
        // Si el trabajo ya está cotizado, mostrar los valores guardados
        if ($trabajo->estado === 'cotizado') {
            $total = $trabajo->Costofactura ?? $calculos['total'];
            $costoprod = $trabajo->Costoproduccion ?? $calculos['costoBaseTotal'];
            $ivaefec = $trabajo->ivaefectivo ?? $calculos['ivaefec'];
            $gananciafinal = $trabajo->gananciaefectivo ?? $calculos['gananciaefec'];
        } else {
            $total = $calculos['total'];
            $costoprod = $calculos['costoBaseTotal'];
            $ivaefec = $calculos['ivaefec'];
            $gananciafinal = $calculos['gananciaefec'];
        }

        return view('livewire.cotizar', [
            'insumos' => $insumos,
            'total' => $total,
            'costoprod' => $costoprod,
            'idtrabajo' => $idtrabajo,
            'ivaefec' => $ivaefec,
            'gananciafinal' => $gananciafinal,
            'trabajo' => $trabajo,
        ]);
    }
}