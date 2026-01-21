<?php

namespace App\Livewire;

use App\Models\Cuentahorro;
use App\Models\CuentaTrabajo;
use App\Models\Ordencompra;
use App\Models\Pagoinsumo;
use App\Models\Trabajo;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class Comprainsumo extends Component
{
    public int $identificador;
    public Ordencompra $ordencompra;
    public $pagos = [];
    public ?float $pago = null;
    public string $fecha;

    public $confirmandoPago = false;
    public $montoConfirmacion;
    public ?int $cuentaSeleccionada = null;

    public function mount(int $identificador): void
    {
        $this->identificador = $identificador;

        // Cargar la orden de compra con sus relaciones
        $this->ordencompra = Ordencompra::with(['insumo.trabajo.cliente'])->findOrFail($identificador);

        // Verificación de seguridad
        if (
            !$this->ordencompra->insumo ||
            !$this->ordencompra->insumo->trabajo ||
            !$this->ordencompra->insumo->trabajo->cliente ||
            $this->ordencompra->insumo->trabajo->cliente->usuario_id !== Auth::user()->id
        ) {
            abort(403, 'Acceso no autorizado a este pago.');
        }

        // Cargar los pagos existentes
        $this->pagos = Pagoinsumo::where('ordencompra_id', $this->ordencompra->id)
            ->orderByDesc('fecha')
            ->get();

        $this->fecha = now()->format('Y-m-d');
    }

    public function rules(): array
    {
        return [
            'pago'  => [
                'required',
                'numeric',
                'min:0.01',
                'max:' . $this->ordencompra->saldo
            ],
            'fecha' => ['required', 'date'],
            'cuentaSeleccionada' => [
                'required',
                'exists:cuentahorros,id'
            ],
        ];
    }

    public function confirmarPago()
    {
        $this->validate();
        $this->montoConfirmacion = $this->pago;
        $this->confirmandoPago = true;
        $this->dispatch('open-modal', id: 'confirmar-pago');
    }

    public function registrarPago()
    {
        DB::transaction(function () {
            // Buscar la cuenta seleccionada
            $cuenta = Cuentahorro::where('user_id', Auth::id())
                ->where('id', $this->cuentaSeleccionada)
                ->first();

            if (! $cuenta) {
                Notification::make()
                    ->title('Cuenta no encontrada')
                    ->danger()
                    ->body('Seleccione una cuenta válida para realizar el pago.')
                    ->send();
                return;
            }

            if ($cuenta->saldo < $this->pago) {
                Notification::make()
                    ->title('Saldo insuficiente')
                    ->danger()
                    ->body('La cuenta seleccionada no tiene fondos suficientes.')
                    ->send();
                return;
            }

            // Registrar movimiento en la cuenta seleccionada
            $cuenta->movimientos()->create([
                'tipo'     => 'retiro',
                'monto'    => $this->pago,
                'fecha'    => $this->fecha,
                'concepto' => 'Pago de insumo - Orden #' . $this->ordencompra->insumo->nombre,
            ]);
            $cuenta->decrement('saldo', $this->pago);

            // Registrar el pago del insumo
            Pagoinsumo::create([
                'ordencompra_id' => $this->ordencompra->id,
                'pago'           => $this->pago,
                'fecha'          => $this->fecha,
            ]);

            // Actualizar saldo de la orden
            $this->ordencompra->saldo -= $this->pago;
            if ($this->ordencompra->saldo <= 0) {
                $this->ordencompra->estado = 'cancelado';
            }
            $this->ordencompra->save();

            // Refrescar pagos
            $this->pagos = Pagoinsumo::where('ordencompra_id', $this->ordencompra->id)->get();
            $this->reset(['pago', 'confirmandoPago', 'montoConfirmacion', 'cuentaSeleccionada']);
            $this->fecha = now()->format('Y-m-d');

            Notification::make()->title('Pago realizado')->success()->send();
        });
    }


    public function render()
    {
        return view('livewire.comprainsumo');
    }
}
