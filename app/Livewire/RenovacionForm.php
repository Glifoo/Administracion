<?php

namespace App\Livewire;

use App\Models\Renovation;
use App\Models\Sell;
use App\Models\User;
use Livewire\Component;
use Filament\Notifications\Notification;

class RenovacionForm extends Component
{
    public $meses = 1;
    public $suscripcion;
    public $isSubmitting = false;

    public function mount()
    {
        $this->suscripcion = auth()->user()->suscripcion;
    }

    public function renovar()
    {

        $this->isSubmitting = true;

        try {
            $this->validate([
                'meses' => 'required|integer|min:1',
            ]);

            Renovation::create([
                'suscripcion_id' => $this->suscripcion->id,
                'fecha' => now(),
                'meses' => $this->meses,
                'estado' => 'pendiente', // Estado inicial
            ]);

            $cuenta = Sell::create([
                'suscripcion_id' => $this->suscripcion->id,
                'total' => number_format($this->suscripcion->paquete->preciounitario * $this->meses, 2),
                'fecha' => now(),
                'concepto' => "renovacion",
            ]);

            // $adminEmails = User::where('rol_id', 1)->pluck('email')->toArray();
            // if (!empty($adminEmails)) {
            //     Mail::to($adminEmails)->send(new Renovacion(
            //         auth()->user(),
            //         $this->suscripcion->paquete,
            //         $this->meses
            //     ));
            // }

            Notification::make()
                ->title('Su solicitud a sido enviada')
                ->success()
                ->send();

            return redirect()->route('filament.home.resources.clients.index');
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Error: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }


    public function render()
    {        
         $sus = auth()->user()->suscripcion;
        return view('livewire.renovacion-form', compact('sus'));
    }
}
