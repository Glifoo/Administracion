<div>
    <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="fi-section-content p-6">
            <div class="mb-8">
                <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-200">
                    Insumos actuales
                </h3>
                <div class="overflow-x-auto">
                    <table class="fi-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5">
                        <thead class="bg-gray-50 dark:bg-white/5">
                            <tr>
                                <th
                                    class="fi-table-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 text-start text-sm font-semibold text-gray-950 dark:text-white">
                                    Nombre
                                </th>
                                <th
                                    class="fi-table-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 text-start text-sm font-semibold text-gray-950 dark:text-white">
                                    Detalle
                                </th>
                                <th
                                    class="fi-table-header-cell px-3 py-3.5 sm:first-of-type:ps-6 sm:last-of-type:pe-6 text-start text-sm font-semibold text-gray-950 dark:text-white">
                                    Costo
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5">
                            @foreach ($insumos as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                                    <td
                                        class="fi-table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-950 dark:text-white">
                                        {{ $item->nombre }}
                                    </td>
                                    <td
                                        class="fi-table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-950 dark:text-white">
                                        {{ $item->detalle }}
                                    </td>
                                    <td
                                        class="fi-table-cell px-3 py-4 whitespace-nowrap text-sm text-gray-950 dark:text-white">
                                        {{ $item->costo }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                      <div class="gap-6 flex justify-end">
                    <a href="{{ route('filament.home.resources.trabajos.index') }}"
                        class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg fi-color-gray fi-size-md fi-btn-color-gray gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-gray-50 text-gray-700 hover:bg-gray-100 focus:ring-gray-500 dark:bg-gray-500/10 dark:text-gray-300 dark:hover:bg-gray-500/20 dark:focus:ring-gray-500">
                        Regresar
                    </a>
                    


                </div>
                </div>
            </div>
        </div>
    </div>
</div>



