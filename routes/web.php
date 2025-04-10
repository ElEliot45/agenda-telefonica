<?php

use App\Http\Controllers\ContactoController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'contactos');

//Con esto se genera en automatico las rutas para CRUD, segun un extraño de stack overflow
Route::resource('contactos', ContactoController::class);
