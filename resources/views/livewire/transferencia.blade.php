<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">

     <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
        Transferencia entre Cuentas
    </h2>

    <form wire:submit.prevent="transferir" class="space-y-6">
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                Cuenta Origen
            </label>
            <select wire:model="cuentaOrigen" disabled 
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                       bg-gray-50 dark:bg-gray-700 
                       text-gray-900 dark:text-gray-100
                       dark:placeholder-gray-400">
                @foreach ($cuentas as $cuenta)
                    @if($cuenta->id == $cuentaOrigen)
                        <option value="{{ $cuenta->id }}">
                            {{ $cuenta->nombre }} - Saldo: ${{ number_format($cuenta->saldo, 2) }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>

        {{-- Cuenta Destino --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                Cuenta Destino *
            </label>
            <select wire:model="cuentaDestino" 
                wire:loading.attr="disabled"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                       bg-white dark:bg-gray-700 
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400
                       focus:border-blue-500 dark:focus:border-blue-400
                       @error('cuentaDestino') border-red-500 dark:border-red-400 @enderror">
                <option value="" class="text-gray-500 dark:text-gray-400">Seleccione una cuenta destino</option>
                @foreach ($cuentas->where('id', '!=', $cuentaOrigen) as $cuenta)
                    <option value="{{ $cuenta->id }}">
                        {{ $cuenta->nombre }} - Saldo: ${{ number_format($cuenta->saldo, 2) }}
                    </option>
                @endforeach
            </select>
            @error('cuentaDestino')
                <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
            @enderror
        </div>

        {{-- Monto --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                Monto a Transferir *
            </label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 dark:text-gray-400">$</span>
                </div>
                <input type="number" wire:model="monto" step="0.01" min="0.01"
                    wire:loading.attr="disabled"
                    placeholder="0.00"
                    class="w-full pl-8 rounded-lg border border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-700 
                           text-gray-900 dark:text-gray-100
                           focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400
                           focus:border-blue-500 dark:focus:border-blue-400
                           @error('monto') border-red-500 dark:border-red-400 @enderror">
            </div>
            @error('monto')
                <span class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</span>
            @enderror
            
            {{-- Mostrar saldo disponible --}}
            @if($cuentaOrigen)
                @php
                    $cuentaSeleccionada = $cuentas->firstWhere('id', $cuentaOrigen);
                @endphp
                @if($cuentaSeleccionada)
                    <div class="mt-2 text-sm text-gray-500 dark:text-white">
                        Saldo disponible: ${{ number_format($cuentaSeleccionada->saldo, 2) }}
                    </div>
                @endif
            @endif
        </div>

        {{-- Fecha --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-white mb-1">
                Fecha de Transferencia *
            </label>
            <input type="date" wire:model="fecha" 
                wire:loading.attr="disabled"
                max="{{ now()->format('Y-m-d') }}"
                class="w-full rounded-lg border border-gray-300 dark:border-gray-600 
                       bg-white dark:bg-gray-700 
                       text-gray-900 dark:text-gray-100
                       focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400
                       focus:border-blue-500 dark:focus:border-blue-400
                       @error('fecha') border-red-500 dark:border-red-400 @enderror">
            @error('fecha')
                <span class="text-sm text-red-600">{{ $message }}</span>
            @enderror
        </div>

        {{-- Botón de transferir --}}
        <div class="pt-4">
            <button type="submit" 
                wire:loading.attr="disabled"
                wire:target="transferir"
                class="w-full mb-4 flex justify-center items-center py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed">
                <span wire:loading.remove wire:target="transferir">
                    Transferir Fondos
                </span>
                <span wire:loading wire:target="transferir">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </div>
    </form>

    {{-- Información adicional --}}
    <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
        <h3 class="text-sm font-medium text-blue-800 mb-2">Información importante:</h3>
        <ul class="text-sm text-blue-700 space-y-1">
            <li>• Las transferencias se realizan al instante</li>
            <li>• No hay comisiones por transferencias entre cuentas</li>
            <li>• El saldo mínimo para transferir es $0.01</li>
            <li>• No puedes transferir a la misma cuenta de origen</li>
        </ul>
    </div>
</div>