<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="estilo/reporte.css">
    <title>Document</title>
</head>

<body>
    <div class="contenedor">
        <div class="lienzo">
            <div class="encabezado">
                {{-- <div class="direccion">
                    <p>
                    </p>

                </div> --}}
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
                <h1>Cotización</h1>
                <p>{{ $trabajo->trabajo }}</p>
                <table>
                    <thead>
                        <tr>
                            <th>Cantidad</th>

                            <th>Detalle</th>
                            <th>Total</th>

                        </tr>
                    </thead>

                    <tbody>

                        <tr>
                            <td class="filas-tabla">
                                {{ $trabajo->cantidad }}
                            </td>

                            <td class="filas-tabla">
                                {!! $trabajo->descripcion !!}
                            </td>
                            <td class="filas-tabla">

                            </td>
                        </tr>

                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Costo</th>
                            <td class="filas-tabla" colspan="1"></td>
                            <td class="filas-tabla" colspan="1"> {{ $total }} Bs.</td>
                        </tr>
                    </tfoot>
                </table>

            </div>
        </div>

    </div>
</body>

</html>
