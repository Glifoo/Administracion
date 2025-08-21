@php
    $idTrabajo = request()->route('record');
@endphp
<x-filament-panels::page>
    @livewire('verinsumo', ['identificador' => $idTrabajo])
</x-filament-panels::page>
