@if ($editandoInsumoId == $insumo->id)
    <tr class="bg-gray-50 dark:bg-gray-800">
        <td colspan="5" class="px-3 py-4">
            <form wire:submit.prevent="actualizarInsumo" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Nombre</label>
                        <input type="text" wire:model="insumoEditado.nombre"
                            class="w-full rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20">
                        @error('insumoEditado.nombre') 
                            <span class="text-xs text-danger-500">{{ $message }}</span> 
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Cantidad</label>
                        <input type="number" wire:model="insumoEditado.cantidad" min="1"
                            class="w-full rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20">
                        @error('insumoEditado.cantidad') 
                            <span class="text-xs text-danger-500">{{ $message }}</span> 
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Costo</label>
                        <input type="number" wire:model="insumoEditado.costo" step="0.01" min="0"
                            class="w-full rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20">
                        @error('insumoEditado.costo') 
                            <span class="text-xs text-danger-500">{{ $message }}</span> 
                        @enderror
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Descripción</label>
                        <input type="text" wire:model="insumoEditado.detalle"
                            class="w-full rounded-lg border-none bg-white px-3 py-2 text-gray-950 shadow-sm ring-1 ring-gray-950/10 focus:ring-2 focus:ring-primary-500 dark:bg-white/5 dark:text-white dark:ring-white/20">
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" wire:click="cancelarEdicion"
                        class="px-3 py-2 text-sm font-semibold rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-3 py-2 text-sm font-semibold rounded-lg bg-primary-600 text-white hover:bg-primary-500 transition">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </td>
    </tr>
@else
    <tr class="hover:bg-gray-50 dark:hover:bg-white/5 transition">
        <td class="px-3 py-4 text-sm text-gray-950 dark:text-white">{{ $insumo->nombre }}</td>
        <td class="px-3 py-4 text-sm text-gray-950 dark:text-white">{{ $insumo->cantidad }}</td>
        <td class="px-3 py-4 text-sm text-gray-950 dark:text-white">${{ number_format($insumo->costo, 2) }}</td>
        <td class="px-3 py-4 text-sm text-gray-950 dark:text-white">{{ $insumo->detalle ?: '-' }}</td>
        <td class="px-3 py-4">
            <div class="flex gap-2">
                <button wire:click="editarInsumo({{ $insumo->id }})"
                    class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600 transition">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                    </svg>
                    Editar
                </button>
                <button wire:click="confirmarEliminacion({{ $insumo->id }})"
                    class="inline-flex items-center gap-1 px-2 py-1 text-xs font-semibold rounded-lg bg-red-50 text-red-700 hover:bg-red-100 dark:bg-red-500/10 dark:text-red-300 dark:hover:bg-red-500/20 transition">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Eliminar
                </button>
            </div>
        </td>
    </tr>
@endif