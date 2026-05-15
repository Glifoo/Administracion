<div>
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="fi-section-content p-4 sm:p-6">
            {{-- Formulario para agregar nuevo insumo --}}
            @include('livewire.partials.form-agregar-insumo')

            <h2 class="text-xl sm:text-2xl font-bold mb-4 text-red-950 dark:text-white mt-6">
                Cotización para: {{ $trabajo }}
            </h2>

            {{-- Tabla de insumos (responsive) --}}
            @include('livewire.partials.tabla-insumos')

            {{-- Resumen de cotización --}}
            @include('livewire.partials.resumen-cotizacion')
        </div>
    </div>

    {{-- Modales --}}
    @include('livewire.partials.modals')
</div>