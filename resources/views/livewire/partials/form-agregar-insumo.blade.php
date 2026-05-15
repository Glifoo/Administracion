<div class="mt-6 sm:mt-8">
    <h3 class="text-lg sm:text-xl font-semibold mb-3 sm:mb-4 text-gray-800 dark:text-gray-200">
        Agregar nuevo insumo
    </h3>

    <form wire:submit.prevent="agregarInsumo" class="space-y-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Nombre *</label>
                <input type="text" wire:model="nuevoInsumo.nombre"
                    class="w-full mt-1 rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20">
                @error('nuevoInsumo.nombre')
                    <span class="text-xs text-danger-500">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Cantidad *</label>
                <input type="number" wire:model="nuevoInsumo.cantidad" min="1"
                    class="w-full mt-1 rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20">
                @error('nuevoInsumo.cantidad')
                    <span class="text-xs text-danger-500">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Costo *</label>
                <input type="number" wire:model="nuevoInsumo.costo" step="0.01" min="0"
                    class="w-full mt-1 rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20">
                @error('nuevoInsumo.costo')
                    <span class="text-xs text-danger-500">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Descripción</label>
                <input type="text" wire:model="nuevoInsumo.detalle"
                    class="w-full mt-1 rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20">
            </div>
        </div>

        <div class="flex justify-end">
            <x-filament::button type="submit" wire:loading.attr="disabled"
                wire:loading.class="opacity-70 cursor-wait" color="primary" spinner="true"
                class="w-full sm:w-auto">
                Agregar Insumo
            </x-filament::button>
        </div>
    </form>
</div>