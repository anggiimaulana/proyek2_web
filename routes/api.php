<?php

use App\Http\Controllers\API\AgamaControllerApi;
use App\Http\Controllers\API\ClientControllerApi;
use App\Http\Controllers\API\HubunganControllerApi;
use App\Http\Controllers\API\JkControllerApi;
use App\Http\Controllers\API\KategoriBantuanControllerApi;
use App\Http\Controllers\API\KategoriPengajuanControllerApi;
use App\Http\Controllers\API\KuwuControllerApi;
use App\Http\Controllers\API\PekerjaanControllerApi;
use App\Http\Controllers\API\PendidikanControllerApi;
use App\Http\Controllers\API\PenghasilanControllerApi;
use App\Http\Controllers\API\StatusPengajuanControllerApi;
use App\Http\Controllers\API\StatusPerkawinanControllerApi;
use App\Http\Controllers\API\UserControllerApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// client
Route::get('/user', [ClientControllerApi::class, 'index']);
Route::get('/user/{id}', [ClientControllerApi::class, 'show']);
Route::post('/user', [ClientControllerApi::class, 'store']);
Route::put('/user/{id}', [ClientControllerApi::class, 'update']);
Route::delete('/user/{id}', [ClientControllerApi::class, 'destroy']);

// kuwu
Route::get('/kuwu', [KuwuControllerApi::class, 'index']);
Route::get('/kuwu/{id}', [KuwuControllerApi::class, 'show']);
Route::post('/kuwu', [KuwuControllerApi::class, 'store']);
Route::put('/kuwu/{id}', [KuwuControllerApi::class, 'update']);
Route::delete('/kuwu/{id}', [KuwuControllerApi::class, 'destroy']);

// user
Route::get('/admin', [UserControllerApi::class, 'index']);
Route::get('/admin/{id}', [UserControllerApi::class, 'show']);
Route::post('/admin', [UserControllerApi::class, 'store']);
Route::put('/admin/{id}', [UserControllerApi::class, 'update']);
Route::delete('/admin/{id}', [UserControllerApi::class, 'destroy']);

// pendukung
Route::get('/jk', [JkControllerApi::class, 'index']);
Route::get('/agama', [AgamaControllerApi::class, 'index']);
Route::get('/pendidikan', [PendidikanControllerApi::class, 'index']);
Route::get('/pekerjaan', [PekerjaanControllerApi::class, 'index']);
Route::get('/status_perkawinan', [StatusPerkawinanControllerApi::class, 'index']);
Route::get('/hubungan', [HubunganControllerApi::class, 'index']);
Route::get('/kategori_pengajuan', [KategoriPengajuanControllerApi::class, 'index']);
Route::get('/kategori_bantuan', [KategoriBantuanControllerApi::class, 'index']);
Route::get('/penghasilan', [PenghasilanControllerApi::class, 'index']);
Route::get('/status_pengajuan', [StatusPengajuanControllerApi::class, 'index']);