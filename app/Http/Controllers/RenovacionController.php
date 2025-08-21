<?php

namespace App\Http\Controllers;

use App\Mail\Renovacion;
use App\Models\Renovation;
use App\Models\Sell;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class RenovacionController extends Controller
{
    public function create($renovacion)
    {
        try {
            $id = Crypt::decrypt($renovacion);
            $user = User::with('suscripcion.paquete')->findOrFail($id);

            // Verificación de seguridad adicional


            return view('resuscripcion.index', [
                'isSubmitting' => false,
                'user' => $user,
                'sus' => $user,
                'encryptedId' => $renovacion
            ]);
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            abort(404, 'Enlace inválido');
        }
    }
     public function store(Request $request, $renovacion)
    {
        $request->validate([
            'meses' => 'required|in:1,3,6'
        ]);

        try {
            $userId = Crypt::decrypt($renovacion);
            $user = User::with('suscripcion')->findOrFail($userId);

            $renovacion = Renovation::create([
                'suscripcion_id' =>  $user->suscripcion->id,
                'fecha' => now(),
                'meses' => $request->meses,
                'estado' => 'pendiente',
            ]);

            $cuenta = Sell::create([
                'suscripcion_id' => $user->suscripcion->id,
                'total' => number_format($user->suscripcion->paquete->preciounitario * $request->meses, 2),
                'fecha' => now(),
                'concepto'=>"renovacion",
            ]);

            $adminEmails = User::where('rol_id', 1)->pluck('email')->toArray();
            
            if (!empty($adminEmails)) {
                Mail::to($adminEmails)->send(new Renovacion(
                    $user,
                    $user->suscripcion->paquete,
                    $request->meses
                ));
            }

            return Redirect::route('inicio')->with('msj', 'solievi');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al procesar: ' . $e->getMessage());
        }
    }

}
