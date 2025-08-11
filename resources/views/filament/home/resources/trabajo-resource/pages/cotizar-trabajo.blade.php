@php
    $idTrabajo = request()->route('record');
@endphp
<x-filament-panels::page>
     @livewire('cotizar', ['identificador' => $idTrabajo])
</x-filament-panels::page>
