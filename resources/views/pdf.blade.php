<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="estilo/reporte.css">
    <title>Historial de pagos</title>
</head>


<body>
    <div class="contenedor">
        <div class="lienzo">
            <div class="encabezado">
                <div class="logo">
                    @if (!empty($user->logo) && file_exists(public_path('storage/' . $user->logo)))
                        <img src="{{ public_path('storage/' . $user->logo) }}" alt="Logo">
                    @else
                        {{-- Logo por defecto o nada --}}
                        <img src="{{ public_path('img/logos/Boton.webp') }}" alt="Logo por defecto">
                    @endif
                </div>
            </div>
            <div class="cuerpo">
                <h1>Historial de pagos</h1>
                </p>
                <table>
                    <thead>
                        <tr>
                            <th>Monto</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($pagos as $item)
                            <tr>
                                <td class="filas-tabla">
                                    {{ $item->pago }}
                                </td>
                                <td class="filas-tabla">
                                    {{ \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="filas-tabla" colspan="1">Total:</td>
                            <th class="filas-tabla" colspan="1"> {{ $suma }} Bs.</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
