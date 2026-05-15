<div class="mb-8">
    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">
        Insumos actuales
    </h3>
    
    <!-- Vista Desktop: Tabla -->
    <div class="hidden md:block overflow-x-auto">
        <table class="fi-table w-full table-auto divide-y divide-gray-200 dark:divide-white/5">
            <thead class="bg-gray-50 dark:bg-white/5">
                <tr>
                    <th class="px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">Nombre</th>
                    <th class="px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">Cantidad</th>
                    <th class="px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">Costo</th>
                    <th class="px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">Descripción</th>
                    <th class="px-3 py-3.5 text-start text-sm font-semibold text-gray-950 dark:text-white">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-white/5">
                @foreach ($insumos as $insumo)
                    @include('livewire.partials.fila-insumo-desktop', ['insumo' => $insumo])
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Vista Mobile: Tarjetas -->
    <div class="md:hidden space-y-4">
        @foreach ($insumos as $insumo)
            @include('livewire.partials.fila-insumo-mobile', ['insumo' => $insumo])
        @endforeach
    </div>
</div>