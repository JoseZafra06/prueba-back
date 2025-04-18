<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Comensal
Route::prefix('/Comensal')->group(function () {
    Route::get('/listComensal', '\App\Http\Controllers\ComensalController@list');
    Route::post('/crudComensal', 'App\Http\Controllers\ComensalController@crud');
})->middleware('auth:api');

//Mesa
Route::prefix('/Mesa')->group(function () {
    Route::get('/listMesa', '\App\Http\Controllers\MesaController@list');
    Route::post('/crudMesa', 'App\Http\Controllers\MesaController@crud');
})->middleware('auth:api');

//Reserva
Route::prefix('/Reserva')->group(function () {
    Route::get('/listReserva', '\App\Http\Controllers\ReservaController@list');
    Route::post('/crudReserva', 'App\Http\Controllers\ReservaController@crud');
})->middleware('auth:api');
