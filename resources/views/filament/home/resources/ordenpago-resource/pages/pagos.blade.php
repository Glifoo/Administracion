@php
    $dato = request()->route('record');
@endphp
<x-filament-panels::page>
     @livewire('realizarpago', ['identificador' => $dato])
</x-filament-panels::page>



