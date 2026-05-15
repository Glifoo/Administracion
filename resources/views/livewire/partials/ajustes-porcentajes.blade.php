<div class="mb-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border border-blue-200 dark:border-blue-800">
    <!-- Header colapsable -->
    <button 
        wire:click="toggleAjustes" 
        class="w-full flex justify-between items-center group"
        type="button"
    >
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-200">
                Ajustar Porcentajes
            </h3>
            
        </div>
        <svg 
            class="w-5 h-5 text-gray-500 transition-transform duration-200 {{ $mostrarAjustes ? 'rotate-180' : '' }}" 
            fill="none" 
            stroke="currentColor" 
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Panel de ajustes -->
    <div class="transition-all duration-300 overflow-hidden" 
         x-data="{ open: @entangle('mostrarAjustes') }"
         x-show="open"
         x-collapse
    >
        <div class="space-y-6 mt-4">
            <!-- Ganancia -->
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Porcentaje de Ganancia
                    </label>
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <input 
                                type="number" 
                                wire:model.live="porcentajeGanancia" 
                                min="0" 
                                max="100" 
                                step="1"
                                class="w-20 text-center rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-2 py-1 text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                {{ $estadoActual === 'cotizado' ? 'disabled' : '' }}
                            >
                            <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-500">%</span>
                        </div>
                    </div>
                </div>
                
                <input 
                    type="range" 
                    wire:model.live="porcentajeGanancia" 
                    min="0" 
                    max="100" 
                    step="1"
                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                    style="accent-color: #10b981;"
                    {{ $estadoActual === 'cotizado' ? 'disabled' : '' }}
                >
                
                <div class="flex items-center gap-2">
                    <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700">
                        <div 
                            class="h-full bg-gradient-to-r from-green-400 to-green-600 rounded-full transition-all duration-300"
                            style="width: {{ $porcentajeGanancia }}%"
                        ></div>
                    </div>
                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400 min-w-[45px]">
                        {{ $porcentajeGanancia }}%
                    </span>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    <button type="button" wire:click="$set('porcentajeGanancia', 0)" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-slate-50 dark:text-gray-300">0%</button>
                    <button type="button" wire:click="$set('porcentajeGanancia', 20)" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-green-100 text-green-700 hover:bg-green-200">20%</button>
                    <button type="button" wire:click="$set('porcentajeGanancia', 25)" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-green-100 text-green-700 hover:bg-green-200">25%</button>
                    <button type="button" wire:click="$set('porcentajeGanancia', 30)" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-green-100 text-green-700 hover:bg-green-200">30%</button>
                    <button type="button" wire:click="$set('porcentajeGanancia', 40)" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-green-100 text-green-700 hover:bg-green-200">40%</button>
                    <button type="button" wire:click="$set('porcentajeGanancia', 50)" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-green-100 text-green-700 hover:bg-green-200">50%</button>
                </div>
            </div>

            <!-- Separador -->
            <div class="border-t border-blue-200 dark:border-blue-800"></div>

            <!-- IVA -->
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Porcentaje de IVA
                    </label>
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <input 
                                type="number" 
                                wire:model.live="porcentajeIVA" 
                                min="0" 
                                max="100" 
                                step="1"
                                class="w-20 text-center rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-2 py-1 text-sm font-semibold text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                {{ $estadoActual === 'cotizado' ? 'disabled' : '' }}
                            >
                            <span class="absolute right-2 top-1/2 -translate-y-1/2 text-xs text-gray-500">%</span>
                        </div>
                    </div>
                </div>
                
                <input 
                    type="range" 
                    wire:model.live="porcentajeIVA" 
                    min="0" 
                    max="100" 
                    step="1"
                    class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                    style="accent-color: #3b82f6;"
                    {{ $estadoActual === 'cotizado' ? 'disabled' : '' }}
                >
                
                <div class="flex items-center gap-2">
                    <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700">
                        <div 
                            class="h-full bg-gradient-to-r from-blue-400 to-blue-600 rounded-full transition-all duration-300"
                            style="width: {{ $porcentajeIVA }}%"
                        ></div>
                    </div>
                    <span class="text-xs font-medium text-gray-600 dark:text-gray-400 min-w-[45px]">
                        {{ $porcentajeIVA }}%
                    </span>
                </div>
                
                <div class="flex flex-wrap gap-2">
                    <button type="button" wire:click="$set('porcentajeIVA', 0)" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200">Exento (0%)</button>
                    <button type="button" wire:click="$set('porcentajeIVA', 16)" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200">Estándar (16%)</button>
                    <button type="button" wire:click="$set('porcentajeIVA', 21)" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-100 text-blue-700 hover:bg-blue-200">General (21%)</button>
                </div>
            </div>

            @if($estadoActual === 'cotizado')
                <div class="text-center text-sm text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-900/20 p-2 rounded-lg">
                    ⚠️ Esta cotización ya está finalizada. No se pueden modificar los porcentajes.
                </div>
            @endif
        </div>
    </div>
</div>