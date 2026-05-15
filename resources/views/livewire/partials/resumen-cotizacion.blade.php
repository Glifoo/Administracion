<div class="mt-6 space-y-3 sm:space-y-4">

    <!-- Panel de ajustes de porcentajes (NUEVO) -->
    @include('livewire.partials.ajustes-porcentajes')

    <!-- Costo producción -->
    <div class="p-3 sm:p-4 bg-gray-50 rounded-lg dark:bg-gray-800">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <span class="text-sm sm:text-base font-semibold text-gray-600 dark:text-gray-400">
                💰 Costo producción:
            </span>
            <span class="text-lg sm:text-xl font-bold text-gray-950 dark:text-white">
                ${{ number_format($costoprod, 2) }}
            </span>
        </div>
    </div>

    <!-- Porcentaje de Ganancia Actual (NUEVO) -->
    <div class="p-3 sm:p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <span class="text-sm sm:text-base font-semibold text-green-700 dark:text-green-400">
                📈 Ganancia aplicada:
            </span>
            <div class="text-right">
                <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">
                    {{ $porcentajeGanancia }}%
                </span>
                <span class="text-lg sm:text-xl font-bold text-green-700 dark:text-green-400">
                    (${{ number_format($gananciafinal, 2) }})
                </span>
            </div>
        </div>
    </div>

    <!-- Reserva para Impuestos -->
    <div class="p-3 sm:p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <span class="text-sm sm:text-base font-semibold text-blue-700 dark:text-blue-400">
                📋 Reserva para Impuestos ({{ $porcentajeIVA }}%):
            </span>
            <span class="text-lg sm:text-xl font-bold text-blue-700 dark:text-blue-400">
                ${{ number_format($ivaefec, 2) }}
            </span>
        </div>
    </div>

    <!-- Total a Facturar -->
    <div
        class="p-4 sm:p-6 bg-gradient-to-r from-primary-50 to-primary-100 dark:from-primary-900/30 dark:to-primary-800/20 rounded-xl border-2 border-primary-200 dark:border-primary-800">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <span class="text-base sm:text-lg font-bold text-primary-700 dark:text-primary-400">
                🎯 Total a Facturar:
            </span>
            <span class="text-2xl sm:text-3xl font-bold text-primary-700 dark:text-primary-400">
                ${{ number_format($total, 2) }}
            </span>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="flex flex-col sm:flex-row gap-3 justify-end mt-6">
        <a href="{{ route('filament.home.resources.trabajos.index') }}"
            class="w-full sm:w-auto text-center px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-zinc-50 dark:text-slate-800 dark:hover:bg-gray-700 transition font-semibold">
            Regresar
        </a>

        @php
            $encryptedId = Crypt::encrypt($idtrabajo);
        @endphp

        <x-filament::button tag="a" href="{{ route('cotizacionodf', $encryptedId) }}" target="_blank"
            color="warning" icon="heroicon-m-document-arrow-down" class="w-full sm:w-auto justify-center">
            Crear PDF
        </x-filament::button>

        <x-filament::button wire:click="confirmarFinalizacion" :disabled="$estadoActual === 'cotizado'" color="primary"
            class="w-full sm:w-auto justify-center">
            <x-heroicon-o-check class="h-5 w-5 mr-2" />
            Terminar Cotización
        </x-filament::button>
    </div>

</div>
