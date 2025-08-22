<?php

namespace App\Livewire;

use App\Models\Ordencompra;
use App\Models\Pagoinsumo;
use App\Models\Trabajo;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;


class Comprainsumo extends Component
{
    public int $identificador;
    public Ordencompra $ordencompra;
    public $pagos = [];
    public ?float $pago = null;
    public string $fecha;

    public $confirmandoPago = false;
    public $montoConfirmacion;

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
            $this->ordencompra->insumo->trabajo->cliente->usuario_id !== auth()->id()
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
            // Crear el nuevo pago
            Pagoinsumo::create([
                'ordencompra_id' => $this->ordencompra->id,
                'pago' => $this->pago,
                'fecha' => $this->fecha
            ]);

            $this->ordencompra->saldo -= $this->pago;

            if ($this->ordencompra->saldo <= 0) {
                $this->ordencompra->estado = 'cancelado'; // Asegúrate que este campo existe en tu modelo
            }

            $this->ordencompra->save();

            $this->pagos = Pagoinsumo::where('ordencompra_id', $this->ordencompra->id)->get();
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
        return view('livewire.comprainsumo');
    }
}
