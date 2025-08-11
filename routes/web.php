<?php

use App\Http\Controllers\InicioController;
use App\Http\Controllers\PlanesController;
use App\Http\Controllers\RenovacionController;
use App\Livewire\RenovacionForm;
use Illuminate\Support\Facades\Route;

Route::controller(InicioController::class)->group(function () {
    Route::get('/', 'index')
        ->name('inicio');
});

Route::get('./home/login', function () {
    return redirect('/home/login');
})->name('usuariologin');

Route::controller(PlanesController::class)->group(function () {
    Route::get('/productos', 'index')
        ->name('planes');
});

Route::get('/home/register/{paquete?}', function ($paquete = null) {
    return redirect()->route('filament.home.auth.register', ['paquete' => $paquete]);
})->name('registro');

Route::get('/renovacion', RenovacionForm::class)->name('renovacion.form');

Route::controller(RenovacionController::class)->group(function () {
    Route::get('/resuscripcion/{renovacion}', 'create')
        ->name('resuscrip');

    Route::post('/resuscripcion/{renovacion}', 'store')
        ->name('resuscripcion.store');
});