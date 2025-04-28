<?php

use App\Http\Controllers\API\AgamaControllerApi;
use App\Http\Controllers\API\ClientControllerApi;
use App\Http\Controllers\API\JkControllerApi;
use App\Http\Controllers\API\PekerjaanControllerApi;
use App\Http\Controllers\API\PendidikanControllerApi;
use App\Http\Controllers\API\StatusPerkawinanControllerApi;
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
Route::get('/jk', [JkControllerApi::class, 'index']);
Route::get('/agama', [AgamaControllerApi::class, 'index']);
Route::get('/pendidikan', [PendidikanControllerApi::class, 'index']);
Route::get('/pekerjaan', [PekerjaanControllerApi::class, 'index']);
Route::get('/status_perkawinan', [StatusPerkawinanControllerApi::class, 'index']);