<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Insumo;
use App\Models\Ordenpago;
use App\Models\Pago;
use App\Models\Trabajo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Swindon\FilamentHashids\Support\HashidsManager;
use Illuminate\Support\Facades\Crypt;

class PdfController extends Controller
{
    public function generate(string $hashid)
    {

        $decoded = HashidsManager::decode($hashid);
        $id = $decoded[0];

        $user = Auth::user();
        $ordenpago = Ordenpago::findOrFail($id);
        $idtrabajo = $ordenpago->trabajo_id;
        $trab = Trabajo::findOrFail($idtrabajo);
        $pago = Pago::where('ordenpago_id', $ordenpago->id)->get();
        $suma = pago::where('ordenpago_id',  $ordenpago->id)->sum('pago');


        if ($ordenpago->trabajo->cliente->usuario_id == $user->id) {

            $pdf = Pdf::loadView('pdf', [
                'user' => $user,
                'pagos' => $pago,
                'suma' => $suma,
            ]);
            return $pdf->stream("OrdenPago.pdf");
        } else {
            abort(403, 'Acceso no autorizado a este pago.');
        }
        // return $pdf->download("OrdenPago-{$record->number}.pdf");
    }

    public function cotizacionpdf(string $trabajoId)
    {
        $user = Auth::user();
        $id = Crypt::decrypt($trabajoId);
        $trabajo = Trabajo::findOrFail($id);
        $items = Insumo::where('trabajo_id', $trabajo->id)->get();
        $total = Insumo::where('trabajo_id',  $trabajo->id)->sum('costo');


        $pdf = Pdf::loadView('cotizacionpdf', [
            'trabajo' => $trabajo,
            'items' => $items,
            'total' => $total,
            'user' => $user,
        ]);
        return $pdf->stream("cotizacion.pdf");
    }
}
