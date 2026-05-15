<x-filament::modal id="confirmar-eliminacion" heading="Confirmar eliminación" width="sm">
    <p class="text-sm text-gray-600">
        ¿Estás seguro de eliminar este insumo?
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
        ¿Estás seguro que deseas finalizar esta cotización? Esta acción no se puede deshacer.
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