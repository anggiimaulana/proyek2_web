<?php

use App\Http\Controllers\API\ClientControllerApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/client', [ClientControllerApi::class, 'index']);
Route::get('/client/{id}', [ClientControllerApi::class, 'show']);
Route::post('/client', [ClientControllerApi::class, 'store']);
Route::put('/client/{id}', [ClientControllerApi::class, 'update']);
Route::delete('/client/{id}', [ClientControllerApi::class, 'destroy']);