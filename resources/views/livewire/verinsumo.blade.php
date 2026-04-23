<div class="space-y-6">
    {{-- Header del trabajo --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <x-heroicon-o-clipboard-document-check class="w-6 h-6 text-primary-500" />
                        {{ $trabajo->trabajo }}
                    </h2>
                    @if ($trabajo->descripcion)
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                            {!! $trabajo->descripcion !!}
                        </p>
                    @endif
                </div>
                <x-filament::badge color="success" class="text-base">
                    {{ $insumos->count() }} Insumos
                </x-filament::badge>
            </div>
        </div>
    </div>

    {{-- Tabla de insumos --}}
    @if ($insumos->count())
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900/50">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Detalle
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Cantidad
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Costo
                            </th>

                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                        @foreach ($insumos as $insumo)
                            <tr class="hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                    {{ $insumo->nombre }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-900 italic">
                                    {{ $insumo->detalle ?: '—' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-right font-medium text-gray-900 dark:text-gray-300">
                                    {{ number_format($insumo->cantidad, 2) }}
                                </td>
                                <td
                                    class="px-6 py-4 text-sm text-right font-bold text-primary-600 dark:text-primary-400">
                                    {{ number_format($insumo->costo, 2) }} Bs
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-100 dark:bg-gray-900/50">
                        <tr>
                            <td colspan="3"
                                class="px-6 py-4 text-right text-sm font-medium text-gray-900 dark:text-white">
                                Total General:
                            </td>
                            <td
                                class="px-6 py-4 text-right text-xl font-extrabold text-primary-600 dark:text-primary-400">
                                {{ number_format($totalInsumos, 2) }} Bs
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <div class="text-center py-12">
                <div class="text-6xl mb-4">📦</div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                    No hay insumos registrados
                </h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Este trabajo aún no tiene insumos asociados.
                </p>
            </div>
        </div>
    @endif
</div>
