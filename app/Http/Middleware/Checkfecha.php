<?php

namespace App\Http\Middleware;

use App\Models\Renovation;
use App\Models\Suscripcion;
use Closure;
use Filament\Notifications\Actions\Action;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;
use Filament\Notifications\Notification;

class Checkfecha
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (!$user->suscripcion) {

            Auth::logout();
            return Redirect::route('inicio')->with('msj', 'sinsuscripcion');
        }

        $fechaActual = Carbon::now();
        $inicio = Carbon::parse($user->suscripcion->fecha_inicio);


        $fechaFin = Carbon::parse($user->suscripcion->fecha_fin);
        $dias = (int) round(now()->diffInDays($fechaFin, false));

      $tieneRenovacionPendiente = Renovation::whereHas('suscripcion', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })
            ->where('estado', 'pendiente')
            ->exists();

        if ($fechaActual->between($inicio, $fechaFin)) {

            // Mostrar la advertencia si quedan pocos días y aún no fue notificado
            if ($dias <= 5 && !$tieneRenovacionPendiente) {

                Notification::make()
                    ->title("¡Le quedan $dias días de suscripción!")
                    ->danger()
                    ->persistent()
                    ->actions([
                        Action::make('renovar')
                            ->label('Renovar ahora')  // Texto del botón
                            ->button()  // Estilo de botón
                            ->color('primary')  // Color (opcional)
                            ->url(route('renovacion.form'))  // URL del formulario
                    ])
                    ->send();
            }


            return $next($request);
        }

        $userId = Auth::id();
        $encryptedId = Crypt::encrypt($userId);

        $suscripcion = Suscripcion::where('user_id', $userId)->first();
        $suscripcion->update(['estado' => '0']);

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return Redirect::route('resuscrip', ['renovacion' => $encryptedId])->with('msj', 'susterminada');
    }
}
