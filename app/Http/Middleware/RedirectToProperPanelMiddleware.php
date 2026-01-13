<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Pages\Dashboard;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToProperPanelMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $currentPanel = filament()->getCurrentPanel()?->getId();
        if ($user->nombreRol() === "Administrador General" && $currentPanel !== 'admin') {
            return redirect()->to(Dashboard::getUrl(panel: 'admin'));
        }
        if ($user->hasRole('Usuario') && $currentPanel !== 'home') {
            return redirect()->to(Dashboard::getUrl(panel: 'home'));
        }
        return $next($request);
    }
}
