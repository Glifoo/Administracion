<?php

namespace App\Livewire;

use App\Models\Cuentahorro;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;


class Transferencia extends Component
{
    public ?int $cuentaOrigen;
    public ?int $cuentaDestino = null;
    public ?float $monto = null;
    public string $fecha;

    public function getCuentasProperty()
    {
        return Cuentahorro::where('user_id', Auth::id())->get();
    }

    public function mount(int $identificador): void
    {
        $this->cuentaOrigen = $identificador;
        $this->fecha = now()->format('Y-m-d');
    }

    public function rules(): array
    {
        return [
            'cuentaOrigen' => ['required', 'exists:cuentahorros,id'],
            'cuentaDestino' => ['required', 'different:cuentaOrigen', 'exists:cuentahorros,id'],
            'monto' => ['required', 'numeric', 'min:0.01'],
            'fecha' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'cuentaDestino.different' => 'La cuenta destino debe ser diferente a la cuenta origen',
        ];
    }

    public function transferir()
    {
        $this->validate();

        try {
            DB::transaction(function () {
                $origen = Cuentahorro::where('user_id', Auth::id())->findOrFail($this->cuentaOrigen);
                $destino = Cuentahorro::where('user_id', Auth::id())->findOrFail($this->cuentaDestino);

                if ($origen->saldo < $this->monto) {
                    throw new \Exception('Saldo insuficiente en cuenta origen');
                }

                // Registrar movimiento en cuenta origen
                $origen->movimientos()->create([
                    'tipo' => 'transferencia',
                    'monto' => $this->monto,
                    'fecha' => $this->fecha,
                    'concepto' => "Transferencia a cuenta: {$destino->nombre}",
                ]);
                $origen->decrement('saldo', $this->monto);

                // Registrar movimiento en cuenta destino
                $destino->movimientos()->create([
                    'tipo' => 'transferencia',
                    'monto' => $this->monto,
                    'fecha' => $this->fecha,
                    'concepto' => "Transferencia desde cuenta: {$origen->nombre}",
                ]);
                $destino->increment('saldo', $this->monto);

                Notification::make()
                    ->title('Transferencia exitosa')
                    ->body("Se transfirió \${$this->monto} de {$origen->nombre} a {$destino->nombre}")
                    ->success()
                    ->send();

                $this->reset(['cuentaDestino', 'monto']);
                $this->fecha = now()->format('Y-m-d');
            });
             return redirect()->route('filament.home.resources.cuentahorros.index');
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error en transferencia')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.transferencia', [
            'cuentas' => $this->cuentas,
        ]);
    }
}
