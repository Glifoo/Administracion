@php
    $dato = request()->route('record');
@endphp
<x-filament-panels::page>
    @livewire('comprainsumo', ['identificador' => $dato])
</x-filament-panels::page>
