<div>
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
        {{-- Información del Insumo --}}
        <div class="mb-6">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-white">
                Insumo: {{ $ordencompra->insumo->nombre }}
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Trabajo: {{ $ordencompra->insumo->trabajo->trabajo }}
            </p>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Cliente: {{ $ordencompra->insumo->trabajo->cliente->nombre }}
            </p>
        </div>
        <div
            class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6">
            {{-- Sección 1: Monto de la Deuda --}}
            <x-filament::grid :default="3" class="gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">TOTAL</p>
                    <x-filament::badge color="primary">
                        ${{ number_format($ordencompra->total, 2) }}
                    </x-filament::badge>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">SALDO</p>
                    <x-filament::badge :color="$ordencompra->saldo > 0 ? 'danger' : 'success'">
                        ${{ number_format($ordencompra->saldo, 2) }}
                    </x-filament::badge>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-300">ESTADO</p>
                    <x-filament::badge :color="$ordencompra->estado == 'cancelado' ? 'success' : 'warning'">
                        {{ strtoupper($ordencompra->estado) }}
                    </x-filament::badge>
                </div>
            </x-filament::grid>

            {{-- Sección 2: Pagos realizados --}}
            <div class="mb-8 mt-6">
                <h1 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Pagos realizados</h1>

                <div class="overflow-x-auto">
                    <table class="fi-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                        <thead class="bg-gray-50 dark:bg-white/5">
                            <tr>
                                <th
                                    class="fi-table-header-cell px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">
                                    Monto
                                </th>
                                <th
                                    class="fi-table-header-cell px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">
                                    Fecha
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                            @forelse($pagos as $pagoItem)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                    <td
                                        class="fi-table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-950 dark:text-white">
                                        ${{ number_format($pagoItem->pago, 2) }}
                                    </td>
                                    <td
                                        class="fi-table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-950 dark:text-white">
                                        {{ \Carbon\Carbon::parse($pagoItem->fecha)->format('d/m/Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2"
                                        class="fi-table-cell px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No se encontraron pagos registrados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Sección 3: Formulario para nuevo pago --}}
            @if ($ordencompra->saldo > 0)
                <div class="mt-6">
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white mb-4">Registrar nuevo pago</h1>

                    <form wire:submit.prevent="save" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="pago"
                                    class="fi-input-label block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Importe *
                                </label>
                                <input type="number" wire:model="pago" id="pago" step="0.01" min="0.01"
                                    max="{{ $ordencompra->saldo }}"
                                    class="fi-input block w-full rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 transition duration-75 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:focus:ring-primary-500">
                                @error('pago')
                                    <span class="text-sm text-danger-500 dark:text-danger-400">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label for="fecha"
                                    class="fi-input-label block text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Fecha *
                                </label>
                                <input type="date" wire:model="fecha" id="fecha"
                                    class="fi-input block w-full rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 transition duration-75 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:focus:ring-primary-500">
                                @error('fecha')
                                    <span class="text-sm text-danger-500 dark:text-danger-400">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div>
                            <label for="cuentaSeleccionada"
                                class="fi-input-label block text-sm font-medium text-gray-700 dark:text-gray-200">
                                Cuenta de ahorro *
                            </label>
                            <select wire:model="cuentaSeleccionada" id="cuentaSeleccionada"
                                class="fi-input block w-full rounded-lg border-none  px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 transition duration-75 focus:ring-2 focus:ring-primary-500   dark:ring-white/20 dark:focus:ring-primary-500">
                                <option value="">Seleccione una cuenta</option>
                                @foreach (\App\Models\Cuentahorro::where('user_id', Auth::id())->get() as $cuenta)
                                    <option value="{{ $cuenta->id }}">{{ $cuenta->nombre }} (Saldo:
                                        ${{ number_format($cuenta->saldo, 2) }})</option>
                                @endforeach
                            </select>
                            @error('cuentaSeleccionada')
                                <span class="text-sm text-danger-500 dark:text-danger-400">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="flex justify-between items-center pt-4">
                            <a href="{{ route('filament.home.resources.ordencompras.index') }}"
                                class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg fi-color-gray fi-size-md fi-btn-color-gray gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-gray-50 text-gray-700 hover:bg-gray-100 focus:ring-gray-500 dark:bg-gray-500/10 dark:text-gray-300 dark:hover:bg-gray-500/20 dark:focus:ring-gray-500">
                                Regresar
                            </a>

                            <button type="button" wire:click="confirmarPago"
                                class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg fi-color-primary fi-size-md fi-btn-color-primary gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-primary-600 text-white hover:bg-primary-500 focus:ring-primary-500 dark:bg-primary-600 dark:hover:bg-primary-500 dark:focus:ring-primary-500">
                                Registrar Pago
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <div class="text-center py-6">
                    <p class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">Esta orden de compra ha sido
                        completada</p>
                    <a href="{{ route('filament.home.resources.ordencompras.index') }}"
                        class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg fi-color-gray fi-size-md fi-btn-color-gray gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-gray-50 text-gray-700 hover:bg-gray-100 focus:ring-gray-500 dark:bg-gray-500/10 dark:text-gray-300 dark:hover:bg-gray-500/20 dark:focus:ring-gray-500">
                        Regresar
                    </a>
                </div>
            @endif

            {{-- Modal de confirmación --}}
            <x-filament::modal id="confirmar-pago" heading="Confirmar Pago"
                subheading="¿Estás seguro que deseas registrar este pago? Esta acción no se puede deshacer."
                width="md">
                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="font-medium">Monto:</span>
                        <span>${{ number_format($montoConfirmacion, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Fecha:</span>
                        <span>{{ \Carbon\Carbon::parse($fecha)->format('d/m/Y') }}</span>
                    </div>
                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Una vez registrado, el pago no podrá ser modificado ni eliminado.
                        </p>
                    </div>
                </div>

                <x-slot name="footer">
                    <div class="flex items-center gap-6 rtl:space-x-reverse">
                        <x-filament::button color="gray" x-on:click="close()"
                            wire:click="$set('confirmandoPago', false)">
                            Cancelar
                        </x-filament::button>

                        <x-filament::button color="primary" wire:click="registrarPago" x-on:click="close()">
                            Confirmar Pago
                        </x-filament::button>
                    </div>
                </x-slot>
            </x-filament::modal>
        </div>
    </div>
