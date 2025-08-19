<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Ordenpago;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPagoOwnership
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ordenpagoId = $request->route('record');

        $ordenpago = Ordenpago::with('trabajo.cliente')->find($ordenpagoId);
        dd("hola");

        if (!$ordenpago || $ordenpago->trabajo->cliente->usuario_id !== auth()->id()) {
            abort(403, 'No tienes permiso para acceder a este pago.');
        }

        return $next($request);
    }
}
