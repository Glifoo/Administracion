<?php

namespace App\Filament\Home\Resources\NoResource\Pages\Auth;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use App\Filament\Home\Resources\NoResource;
use App\Mail\Pedidos;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Resources\Pages\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Paquete;
use App\Models\Sell;
use App\Models\Suscripcion;
use App\Models\User;
use Filament\Forms\Components\TextInput;
use Carbon\CarbonImmutable as Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Filament\Forms\Get;
use Filament\Forms\Set;

class Register extends BaseRegister
{
    public ?int $paquete_id = null;

    public function mount(): void
    {
        parent::mount();
        $request = app(Request::class);

        $paqueteEncriptado = $request->query('paquete');

        if ($paqueteEncriptado) {
            try {
                $paqueteId = Crypt::decrypt($paqueteEncriptado);
                $this->paquete_id = $paqueteId;

                if (Paquete::find($paqueteId)) {
                    $this->form->fill([
                        'paquete_id' => $paqueteId
                    ]);
                }
            } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {

                abort(403, 'El paquete proporcionado no es válido.');
            }
        }
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getLastNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPhoneNumberFormComponent(),
                        $this->getPaqueteFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getLastMesesSuscripcion(),
                        $this->getCostoFinalComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getPaqueteFormComponent(): Component
    {
        return Select::make('paquete_id')
            ->label('Selecciona tu paquete')
            ->options(Paquete::pluck('nombre', 'id'))
            ->required()
            ->default($this->paquete_id)
            ->disabled(fn() => !is_null($this->paquete_id))
            ->hidden(fn() => !is_null($this->paquete_id));
    }

    protected function getLastNameFormComponent(): Component
    {
        return TextInput::make('lastname')
            ->label(__('Apellido'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getLastMesesSuscripcion(): Component
    {
        return TextInput::make('meses')
            ->label(__('Meses de suscripción'))
            ->numeric()
            ->minValue(1)
            ->default(1)
            ->required()
            ->live()
            ->afterStateUpdated(fn($state, Set $set, Get $get) => $this->actualizarCostoFinal($set, $get));
    }

    protected function getCostoFinalComponent(): Component
    {
        return TextInput::make('costo_final')
            ->label(__('Costo Final'))
            ->readOnly()
            ->dehydrated(false); 
    }

    protected function actualizarCostoFinal(Set $set, Get $get): void
    {
        $paqueteId = $get('paquete_id');
        $meses = (int) ($get('meses') ?? 1);
        if ($paqueteId && $meses > 0) {
            $paquete = Paquete::find($paqueteId);
            if ($paquete) {
                $total = $paquete->calcularPrecioFinal($meses);
                $set('costo_final', 'Bs ' . number_format($total, 2));
                return;
            }
        }

        $set('costo_final', 'Bs 0.00');
    }
    protected function getPhoneNumberFormComponent(): Component
    {
        return TextInput::make('phone')
            ->label(__('Celular'))
            ->tel()
            ->required()
            ->autofocus();
    }

    protected function handleRegistration(array $data): User
    {
        if (!isset($data['paquete_id'])) {
            $data['paquete_id'] = $this->paquete_id;

            if (is_null($data['paquete_id'])) {
                throw new \RuntimeException('Se debe seleccionar un paquete para el registro');
            }
        }

        // 1. Crear el usuario
        $user = User::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
        ]);

        // Crear suscripción
        $paquete = Paquete::findOrFail($data['paquete_id']);
        $meses   = max(1, (int)($data['meses'] ?? 1));

        $fechaInicio = now();
        $meses = max(1, (int)($data['meses'] ?? 1)); // Asegurar mínimo 1 mes
        $fechaFin = $fechaInicio->copy()->addMonths($meses);


        $suscripcion = Suscripcion::create([
            'user_id' => $user->id,
            'paquete_id' => $data['paquete_id'],
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin,
        ]);

        // 3 Crear la venta 
        $total = $paquete->calcularPrecioFinal($meses);
        $cuenta = Sell::create([
            'suscripcion_id' => $suscripcion->id,
            'total'          => $total,
            'fecha' => now(),
            'concepto' => "suscripcion",
        ]);

        // 6. Envio email
        $adminEmails = User::where('rol_id', 1)->pluck('email')->toArray();
        if (!empty($adminEmails)) {
            Mail::to($adminEmails)->send(new Pedidos($user, $paquete, $meses));
        }
        return $user;
    }
    protected function getRedirectUrl(): string
    {
        return Redirect::route('inicio')->with('msj', 'suscripcion');
    }
}
