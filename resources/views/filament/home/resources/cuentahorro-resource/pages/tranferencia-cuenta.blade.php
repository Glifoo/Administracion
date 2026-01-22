@php
    $dato = request()->route('record');
@endphp
<x-filament-panels::page>
    @livewire('Transferencia', ['identificador' => $dato])
</x-filament-panels::page>


