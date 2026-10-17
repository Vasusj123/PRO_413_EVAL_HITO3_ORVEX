<?php

use App\Http\Controllers\SolicitudSoporteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SolicitudSoporteController::class, 'index'])->name('solicitudes.index');
Route::post('/solicitudes', [SolicitudSoporteController::class, 'store'])->name('solicitudes.store');
Route::patch('/solicitudes/{solicitud}/estado', [SolicitudSoporteController::class, 'updateEstado'])
    ->name('solicitudes.estado');

