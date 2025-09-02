<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cotización - {{ $trabajo->trabajo }}</title>
    <link rel="stylesheet" href="estilo/reporte.css" />
</head>

<body>
    <div class="contenedor">
        <div class="lienzo">
            <!-- Encabezado con fecha -->
            <div class="encabezado">
                <div class="logo">
                    @if (!empty($user->logo) && file_exists(public_path('storage/' . $user->logo)))
                        <img src="{{ public_path('storage/' . $user->logo) }}" alt="Logo">
                    @else
                        <img src="{{ public_path('img/logos/Boton.webp') }}" alt="Logo por defecto">
                    @endif
                </div>

                <div class="fecha-cotizacion">
                    <div class="fecha">
                        <strong>Fecha:</strong> {{ now()->format('d/m/Y') }}
                    </div>
                </div>
            </div>

            <!-- Cuerpo de la cotización -->
            <div class="cuerpo">
                <h1>COTIZACIÓN</h1>

                <div class="cabezeracoti">
                    <h2><b>Señor(es):</b> {{ $trabajo->cliente->nombre }}</h2>
                    <h2><b>Proyecto:</b> {{ $trabajo->trabajo }}</h2>
                    @if ($trabajo->cliente->direccion)
                        <h2><b>Dirección:</b> {{ $trabajo->cliente->direccion }}</h2>
                    @endif
                </div>

                <table>
                    <thead>
                        <tr>
                            <th style="width: 15%">Cantidad</th>
                            <th style="width: 60%">Detalle</th>
                            <th style="width: 10%">Precio unitario</th>
                            <th style="width: 25%;text-align:center">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="filas-tabla" style="text-align: center;">
                                {{ $trabajo->cantidad }}
                            </td>
                            <td class="filas-tabla" style="text-align: left;">
                                {!! $trabajo->descripcion !!}
                            </td>
                            <td class="filas-tabla" style="text-align: center;">
                                {{ number_format($preciounitario, 2) }}
                            </td>
                            <td class="filas-tabla">
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3">TOTAL</th>
                            <td style="text-align: right; font-weight: bold; color: #2c5282;">
                                {{ number_format($total, 2) }} Bs.
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="total-destacado">
                    TOTAL: {{ number_format($total, 2) }} Bs.
                </div>

                <div class="piefinal">
                    <p><b>Son:</b> {{ $totalEnLetras }}</p>
                    <p><b>Forma de pago:</b> {{ $trabajo->forma_pago ?? 'A convenir' }}</p>
                    <p><b>Plazo de entrega:</b> {{ $trabajo->plazo_entrega ?? 'A convenir' }}</p>
                    <p><b>Validez de la cotización:</b> 30 días</p>
                </div>
                <!-- Información adicional -->
                <div class="info-adicional">
                    <p><b>Nota:</b> Esta cotización incluye mano de obra y materiales especificados.
                        Precios sujetos a cambio sin previo aviso. Validez sujeta a disponibilidad de materiales.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
