<?php

use App\Http\Controllers\InicioController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PlanesController;
use App\Http\Controllers\RenovacionController;
use App\Http\Middleware\Checkfecha;
use App\Http\Middleware\VerificarSuscripcionActiva;
use App\Livewire\RenovacionForm;
use App\Models\Ordenpago;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Http\Middleware\Authenticate;

Route::get('./home/login', function () {
    return redirect('/home/login');
})->name('usuariologin');

Route::controller(PlanesController::class)->group(function () {
    Route::get('/', 'index')
        ->name('inicio');
});

Route::middleware([Authenticate::class, Checkfecha::class, VerificarSuscripcionActiva::class])->group(function () {
    Route::get('ordenpago/{record}/pdf', [PdfController::class, 'generate'])
        ->name('ordenpago.pdf');

    Route::get('trabajos/{record}', [PdfController::class, 'pdfcotizacion'])
        ->name('pdfcotizacion');

    Route::get('trabajos/{record}/pdf', [PdfController::class, 'cotizacionpdf'])
        ->name('cotizacionodf');
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
