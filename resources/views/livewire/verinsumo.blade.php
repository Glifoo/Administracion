<div>
    <h2>{{ $trabajo->trabajo }}</h2>

    @if ($insumos->count())
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Detalle</th>
                    <th>Cantidad</th>
                    <th>Costo</th>                    
                   
                </tr>
            </thead>
            <tbody>
                @foreach ($insumos as $insumo)
                    <tr>
                        <td>{{ $insumo->nombre }}</td>
                        <td>{{ $insumo->detalle }}</td>
                        <td>{{ $insumo->cantidad }}</td>
                        <td>{{ number_format($insumo->costo, 2) }} Bs</td>                       
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5"><strong>Total:</strong></td>
                    <td><strong>{{ number_format($totalInsumos, 2) }} Bs</strong></td>
                </tr>
            </tfoot>
        </table>
    @else
        <p>No hay insumos registrados</p>
    @endif
</div>
