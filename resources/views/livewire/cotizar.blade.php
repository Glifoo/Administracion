<div>
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="fi-section-content p-6">


            <!-- Formulario para agregar nuevo insumo -->
            <div class="mt-8">
                <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">
                    Agregar nuevo insumo
                </h3>

                <form wire:submit.prevent="agregarInsumo" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="nombre"
                                class="fi-input-label block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Nombre
                            </label>
                            <input type="text" wire:model="nuevoInsumo.nombre" id="nombre"
                                class="fi-input block w-full rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 transition duration-75 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:focus:ring-primary-500">
                            @error('nuevoInsumo.nombre')
                                <span class="text-sm text-danger-500 dark:text-danger-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="cantidad"
                                class="fi-input-label block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Cantidad
                            </label>
                            <input type="number" wire:model="nuevoInsumo.cantidad" id="cantidad" min="1"
                                class="fi-input block w-full rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 transition duration-75 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:focus:ring-primary-500">
                            @error('nuevoInsumo.cantidad')
                                <span class="text-sm text-danger-500 dark:text-danger-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="costo"
                                class="fi-input-label block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Costo
                            </label>
                            <input type="number" wire:model="nuevoInsumo.costo" id="costo" step="0.01"
                                min="0"
                                class="fi-input block w-full rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 transition duration-75 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:focus:ring-primary-500">
                            @error('nuevoInsumo.costo')
                                <span class="text-sm text-danger-500 dark:text-danger-400">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label for="detalle"
                                class="fi-input-label block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Descripción
                            </label>
                            <input type="text" wire:model="nuevoInsumo.detalle" id="detalle"
                                class="fi-input block w-full rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 transition duration-75 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:focus:ring-primary-500">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <x-filament::button type="submit" wire:loading.attr="disabled"
                            wire:loading.class="fi-opacity-70 fi-cursor-wait" wire:target="agregarInsumo"
                            color="primary" spinner="true" class="mt-4">
                            Agregar Insumo
                        </x-filament::button>
                    </div>
                </form>
            </div>
            <h2 class="text-2xl font-bold mb-4 text-red-950 dark:text-white mt-6">
                Cotización para: {{ $trabajo }}
            </h2>
            <!-- Mostrar insumos existentes -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">
                    Insumos actuales
                </h3>
                <div class="overflow-x-auto">
                    {{-- tabla de insumos --}}
                    <table class="fi-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                        <thead class="bg-gray-50 dark:bg-white/5">
                            <tr>
                                <th
                                    class="fi-table-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 text-start text-sm font-semibold text-gray-950 dark:text-white">
                                    Nombre
                                </th>
                                <th
                                    class="fi-table-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 text-start text-sm font-semibold text-gray-950 dark:text-white">
                                    Cantidad
                                </th>
                                <th
                                    class="fi-table-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 text-start text-sm font-semibold text-gray-950 dark:text-white">
                                    Costo
                                </th>

                                <th
                                    class="fi-table-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 text-start text-sm font-semibold text-gray-950 dark:text-white">
                                    Descripción
                                </th>
                                <th
                                    class="fi-table-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 text-start text-sm font-semibold text-gray-950 dark:text-white">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        {{-- tabla de insumos cuerpo --}}
                        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                            @foreach ($insumos as $insumo)
                                @if ($editandoInsumoId == $insumo->id)
                                    <tr class="bg-gray-50 dark:bg-gray-800 mb-4">
                                        <td colspan="6" class="px-3 py-4">
                                            <form wire:submit.prevent="actualizarInsumo" class="space-y-4">
                                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                                    <div>
                                                        <label
                                                            class="fi-input-label block text-sm font-medium text-gray-700 dark:text-gray-200">
                                                            Nombre
                                                        </label>
                                                        <input type="text" wire:model="insumoEditado.nombre"
                                                            class="fi-input block w-full rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 transition duration-75 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:focus:ring-primary-500">
                                                        @error('insumoEditado.nombre')
                                                            <span
                                                                class="text-sm text-danger-500 dark:text-danger-400">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="fi-input-label block text-sm font-medium text-gray-700 dark:text-gray-200">
                                                            Cantidad
                                                        </label>
                                                        <input type="number" wire:model="insumoEditado.cantidad"
                                                            min="1"
                                                            class="fi-input block w-full rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 transition duration-75 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:focus:ring-primary-500">
                                                        @error('insumoEditado.cantidad')
                                                            <span
                                                                class="text-sm text-danger-500 dark:text-danger-400">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="fi-input-label block text-sm font-medium text-gray-700 dark:text-gray-200">
                                                            Costo
                                                        </label>
                                                        <input type="number" wire:model="insumoEditado.costo"
                                                            step="0.01" min="0"
                                                            class="fi-input block w-full rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 transition duration-75 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:focus:ring-primary-500">
                                                        @error('insumoEditado.costo')
                                                            <span
                                                                class="text-sm text-danger-500 dark:text-danger-400">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div>
                                                        <label
                                                            class="fi-input-label block text-sm font-medium text-gray-700 dark:text-gray-200">
                                                            Descripción
                                                        </label>
                                                        <input type="text" wire:model="insumoEditado.detalle"
                                                            class="fi-input block w-full rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 transition duration-75 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:focus:ring-primary-500">
                                                    </div>
                                                </div>

                                                <div class="flex justify-end gap-4">
                                                    <button type="button" wire:click="cancelarEdicion"
                                                        class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg fi-color-gray fi-size-md fi-btn-color-gray mt-4 gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-gray-50 text-gray-700 hover:bg-gray-100 focus:ring-gray-500 dark:bg-gray-500/10 dark:text-gray-300 dark:hover:bg-gray-500/20 dark:focus:ring-gray-500">
                                                        Cancelar
                                                    </button>
                                                    <button type="submit"
                                                        class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg fi-color-primary fi-size-md fi-btn-color-primary mt-4 ml-4 gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-primary-600 text-white hover:bg-primary-500 focus:ring-primary-500 dark:bg-primary-600 dark:hover:bg-primary-500 dark:focus:ring-primary-500 ">
                                                        Guardar Cambios
                                                    </button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                @else
                                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                        <td
                                            class="fi-table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-950 dark:text-white">
                                            {{ $insumo->nombre }}
                                        </td>
                                        <td
                                            class="fi-table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-950 dark:text-white">
                                            {{ $insumo->cantidad }}
                                        </td>
                                        <td
                                            class="fi-table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-950 dark:text-white">
                                            ${{ number_format($insumo->costo, 2) }}
                                        </td>

                                        <td class="fi-table-cell px-3 py-4 text-sm text-gray-950 dark:text-white">
                                            {{ $insumo->detalle }}
                                        </td>
                                        {{-- tabla de insumos --}}
                                        {{-- botones de accion --}}
                                        <td
                                            class="fi-table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-950 dark:text-white">
                                            <div class="flex gap-4">
                                                <button wire:click="editarInsumo({{ $insumo->id }})"
                                                    class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg fi-color-gray fi-size-sm fi-btn-color-gray gap-1 px-2 py-1 text-xs inline-grid shadow-sm bg-gray-50 text-gray-700 hover:bg-gray-100 focus:ring-gray-500 dark:bg-gray-500/10 dark:text-gray-300 dark:hover:bg-gray-500/20 dark:focus:ring-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                    Editar
                                                </button>

                                                <button wire:click="confirmarEliminacion({{ $insumo->id }})"
                                                    class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg fi-color-danger fi-size-sm fi-btn-color-danger gap-1 px-2 py-1 text-xs inline-grid shadow-sm bg-danger-50 text-danger-700 hover:bg-danger-100 focus:ring-danger-500 dark:bg-danger-500/10 dark:text-danger-300 dark:hover:bg-danger-500/20 dark:focus:ring-danger-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    Eliminar
                                                </button>
                                            </div>
                                        </td>
                                        {{-- botones de accion --}}
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 p-4 bg-gray-50 rounded-lg dark:bg-gray-800 mb-4">
                    <div class="flex justify-between items-center mt-2 text-lg font-bold">
                        <span class="text-gray-950 dark:text-white">Costo producción:</span>
                        <span class="text-gray-950 dark:text-white">${{ number_format($costoprod, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-4 bg-gray-50 rounded-lg dark:bg-gray-800">
                <div class="flex justify-between items-center mt-2 text-lg font-bold">
                    <span class="text-gray-950 dark:text-white">Precio Final:</span>
                    <span class="text-gray-950 dark:text-white">${{ number_format($total, 2) }}</span>
                </div>

                <!-- Botón para terminar cotización -->
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-end mt-6">
                    <!-- Botón Regresar -->
                    <a href="{{ route('filament.home.resources.trabajos.index') }}"
                        class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg fi-color-gray fi-size-md fi-btn-color-gray gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-gray-50 text-gray-700 hover:bg-gray-100 focus:ring-gray-500 dark:bg-gray-500/10 dark:text-gray-300 dark:hover:bg-gray-500/20 dark:focus:ring-gray-500 w-full sm:w-auto text-center">
                        Regresar
                    </a>

                    @php
                        $encryptedId = Crypt::encrypt($idtrabajo);
                    @endphp

                    <!-- Botón PDF -->
                    <x-filament::button tag="a" href="{{ route('cotizacionodf', $encryptedId) }}"
                        target="_blank" color="warning" icon="heroicon-m-document-arrow-down"
                        class="w-full sm:w-auto justify-center">
                        Crear PDF
                    </x-filament::button>

                    <!-- Botón Terminar -->
                    <x-filament::button wire:click="confirmarFinalizacion" :disabled="$estadoActual === 'completado'" color="primary"
                        class="w-full sm:w-auto justify-center">
                        <x-heroicon-o-check class="h-5 w-5 mr-2" />
                        Terminar Cotización
                    </x-filament::button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de confirmación para eliminar -->
    <x-filament::modal id="confirmar-eliminacion" heading="Confirmar eliminación" width="sm">
        <p class="text-sm text-gray-600">
            ¿Estás seguro de eliminar este insumo?.
        </p>
        <x-slot name="footer">
            <div class="flex items-center gap-6 rtl:space-x-reverse">
                <x-filament::button color="gray" x-on:click="close()">
                    Cancelar
                </x-filament::button>

                <x-filament::button color="danger" wire:click="eliminarInsumo" x-on:click="close()">
                    Eliminar
                </x-filament::button>
            </div>
        </x-slot>
    </x-filament::modal>

    <x-filament::modal id="confirmar-finalizacion" heading="¿Finalizar cotización?" width="sm">
        <p class="text-sm text-gray-600">
            ¿Estás seguro que deseas finalizar esta cotización? Esta acción no se puede deshacer."
        </p>

        <x-slot name="footer">
            <div class="flex justify-end gap-6 rtl:space-x-reverse">
                <x-filament::button color="gray" x-on:click="close()">
                    Cancelar
                </x-filament::button>

                <x-filament::button color="success" wire:click="terminarCotizacion" x-on:click="close()">
                    Confirmar
                </x-filament::button>
            </div>
        </x-slot>
    </x-filament::modal>
</div>
