<?php

namespace App\Livewire;

use App\Models\Cuentahorro;
use App\Models\Movimientoahorro;
use App\Models\Ordenpago;
use App\Models\Pago;
use App\Models\Trabajo;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class Realizarpago extends Component
{
    public int $identificador;
    public Ordenpago $ordenpago;
    public ?Trabajo $trabajo = null;
    public $pagos = [];
    public ?float $pago = null;
    public string $fecha;

    public $confirmandoPago = false;
    public $montoConfirmacion;

    public function mount(int $identificador): void
    {
        $user = Auth::user();
        $this->identificador = $identificador;

        $this->ordenpago = Ordenpago::with(['trabajo.cliente'])->findOrFail($identificador);

        if (
            !$this->ordenpago->trabajo ||
            !$this->ordenpago->trabajo->cliente ||
            $this->ordenpago->trabajo->cliente->usuario_id !== $user->id
        ) {
            abort(403, 'Acceso no autorizado a este pago.');
        }
        $this->trabajo    = $this->ordenpago->trabajo;
        $this->pagos      = Pago::where('ordenpago_id', $this->ordenpago->id)
            ->orderByDesc('fecha')
            ->get();

        $this->fecha = now()->format('Y-m-d');
    }

    public function rules(): array
    {
        return [
            'pago'  => ['required', 'numeric', 'min:0.01', 'max:' . $this->ordenpago->saldo],
            'fecha' => ['required', 'date'],
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
            Pago::create([
                'ordenpago_id' => $this->ordenpago->id,
                'pago' => $this->pago,
                'fecha' => $this->fecha
            ]);

            $this->ordenpago->saldo -= $this->pago;

            if ($this->ordenpago->saldo <= 0) {
                $this->ordenpago->estado = 'cancelado'; 
            }

            $this->ordenpago->save();

            if ($this->ordenpago->trabajo->cuenta) {

                $cuenta = Cuentahorro::where('user_id', Auth::id())->first();

                if (!$cuenta) {
                    throw new \Exception('El usuario no tiene una cuenta de ahorro creada');
                }

                Movimientoahorro::create([
                    'cuenta_ahorro_id' => $cuenta->id,
                    'tipo'             => 'deposito',
                    'monto'            => $this->pago,
                    'fecha'            => $this->fecha,
                    'concepto'         => 'Pago recibido - ' . $this->ordenpago->trabajo->trabajo,
                ]);
                $cuenta->increment('saldo', $this->pago);
            }

            $this->pagos = Pago::where('ordenpago_id', $this->ordenpago->id)->get();
            $this->reset(['pago', 'confirmandoPago', 'montoConfirmacion']);
            $this->fecha = now()->format('Y-m-d');

            Notification::make()
                ->title('Pago realizado')
                ->success()
                ->send();
        });
    }
    public function render()
    {
        return view('livewire.realizarpago');
    }
}
