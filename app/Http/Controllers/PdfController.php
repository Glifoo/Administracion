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

        $costoprod = Insumo::where('trabajo_id',  $trabajo->id)->sum('costo');
        $parcial = $costoprod + $trabajo->manobra;

        $ganancia = $parcial * $trabajo->ganancia / 100;
        $totalconganancia = $costoprod + $ganancia;

        if ($trabajo->iva > 0) {
            $iva = $totalconganancia * $trabajo->iva / 100;
            $total = $totalconganancia   + $iva;
        } else {
            $total = $totalconganancia + $ganancia;
            $iva = 0;
        }
        $preciounitario = $total / $trabajo->cantidad;
        $totalEnLetras = $this->montoEnLetras($total);

        if ($trabajo->cliente->usuario_id == $user->id) {
            $pdf = Pdf::loadView('cotizacionpdf', [
                'trabajo' => $trabajo,
                'total' => $total,
                'user' => $user,
                'totalEnLetras' => $totalEnLetras,
                'preciounitario' => $preciounitario,
            ]);
            $nombreArchivo = "cotizacion_{$trabajo->trabajo}.pdf";
            return $pdf->stream($nombreArchivo);
        } else {
            abort(403, 'Acceso no autorizado a esta cotizacion.');
        }
    }

    function montoEnLetras($monto)
    {
        $formatter = new \NumberFormatter("es", \NumberFormatter::SPELLOUT);

        $entero = floor($monto);
        $centavos = round(($monto - $entero) * 100);

        $literal = $formatter->format($entero);
        $centavosTexto = str_pad($centavos, 2, "0", STR_PAD_LEFT);

        return ucfirst($literal) . " con {$centavosTexto}/100 bolivianos";
    }
    public function pdfcotizacion(string $hashid)
    {
        $user = Auth::user();
        $decoded = HashidsManager::decode($hashid);
        $id = $decoded[0];
        $trabajo = Trabajo::findOrFail($id);

        $costoprod = Insumo::where('trabajo_id',  $trabajo->id)->sum('costo');
        $parcial = $costoprod + $trabajo->manobra;

        $ganancia = $parcial * $trabajo->ganancia / 100;
        $totalconganancia = $costoprod + $ganancia;

        if ($trabajo->iva > 0) {
            $iva = $totalconganancia * $trabajo->iva / 100;
            $total = $totalconganancia   + $iva;
        } else {
            $total = $totalconganancia + $ganancia;
            $iva = 0;
        }
        $preciounitario = $total / $trabajo->cantidad;
        $totalEnLetras = $this->montoEnLetras($total);

        if ($trabajo->cliente->usuario_id == $user->id) {
            $pdf = Pdf::loadView('cotizacionpdf', [
                'trabajo' => $trabajo,
                'total' => $total,
                'user' => $user,
                'totalEnLetras' => $totalEnLetras,
                'preciounitario' => $preciounitario,
            ]);
            $nombreArchivo = "cotizacion_{$trabajo->trabajo}.pdf";
            return $pdf->stream($nombreArchivo);
        } else {
            abort(403, 'Acceso no autorizado a esta cotizacion.');
        }
    }
}
