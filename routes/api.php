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
use App\Http\Controllers\API\PengajuanControllerApi;
use App\Http\Controllers\API\PengajuanSkBelumMenikahControllerApi;
use App\Http\Controllers\API\PengajuanSkPekerjaanControllerApi;
use App\Http\Controllers\API\PengajuanSkpotBeasiswaControllerApi;
use App\Http\Controllers\API\PengajuanSkpPengajuanBantuanControllerApi;
use App\Http\Controllers\API\PengajuanSkStatusControllerApi;
use App\Http\Controllers\API\PengajuanSktmBeasiswaControllerApi;
use App\Http\Controllers\API\PengajuanSktmListrikControllerApi;
use App\Http\Controllers\API\PengajuanSktmSekolahControllerApi;
use App\Http\Controllers\API\PengajuanSkUsahaControllerApi;
use App\Http\Controllers\API\PenghasilanControllerApi;
use App\Http\Controllers\API\StatusPengajuanControllerApi;
use App\Http\Controllers\API\StatusPerkawinanControllerApi;
use App\Http\Controllers\API\UserControllerApi;
use App\Http\Controllers\Auth\AuthController;
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
Route::get('/status-perkawinan', [StatusPerkawinanControllerApi::class, 'index']);
Route::get('/hubungan', [HubunganControllerApi::class, 'index']);
Route::get('/kategori-pengajuan', [KategoriPengajuanControllerApi::class, 'index']);
Route::get('/kategori-bantuan', [KategoriBantuanControllerApi::class, 'index']);
Route::get('/penghasilan', [PenghasilanControllerApi::class, 'index']);
Route::get('/status-pengajuan', [StatusPengajuanControllerApi::class, 'index']);

// client auth
Route::post('/client/login', [AuthController::class, 'login']);
Route::group(['middleware' => 'auth:sanctum'], function () {
    // Pengajuan skbm
    Route::get('/skbm', [PengajuanSkBelumMenikahControllerApi::class, 'index']);
    Route::get('/skbm/{id}', [PengajuanSkBelumMenikahControllerApi::class, 'show']);
    Route::put('/skbm/{id}', [PengajuanSkBelumMenikahControllerApi::class, 'update']);
    Route::post('/skbm', [PengajuanSkBelumMenikahControllerApi::class, 'store']);

    // Pengajuan skp
    Route::get('/skp', [PengajuanSkPekerjaanControllerApi::class, 'index']);
    Route::get('/skp/{id}', [PengajuanSkPekerjaanControllerApi::class, 'show']);
    Route::put('/skp/{id}', [PengajuanSkPekerjaanControllerApi::class, 'update']);
    Route::post('/skp', [PengajuanSkPekerjaanControllerApi::class, 'store']);

    // Pengajuan skpot
    Route::get('/skpot', [PengajuanSkpotBeasiswaControllerApi::class, 'index']);
    Route::get('/skpot/{id}', [PengajuanSkpotBeasiswaControllerApi::class, 'show']);
    Route::put('/skpot/{id}', [PengajuanSkpotBeasiswaControllerApi::class, 'update']);
    Route::post('/skpot', [PengajuanSkpotBeasiswaControllerApi::class, 'store']);

    // Pengajuan sks
    Route::get('/sks', [PengajuanSkStatusControllerApi::class, 'index']);
    Route::get('/sks/{id}', [PengajuanSkStatusControllerApi::class, 'show']);
    Route::put('/sks/{id}', [PengajuanSkStatusControllerApi::class, 'update']);
    Route::post('/sks', [PengajuanSkStatusControllerApi::class, 'store']);

    // Pengajuan sktm beasiswa
    Route::get('/sktm-beasiswa', [PengajuanSktmBeasiswaControllerApi::class, 'index']);
    Route::get('/sktm-beasiswa/{id}', [PengajuanSktmBeasiswaControllerApi::class, 'show']);
    Route::put('/sktm-beasiswa/{id}', [PengajuanSktmBeasiswaControllerApi::class, 'update']);
    Route::post('/sktm-beasiswa', [PengajuanSktmBeasiswaControllerApi::class, 'store']);

    // Pengajuan sktm listrik
    Route::get('/sktm-listrik', [PengajuanSktmListrikControllerApi::class, 'index']);
    Route::get('/sktm-listrik/{id}', [PengajuanSktmListrikControllerApi::class, 'show']);
    Route::put('/sktm-listrik/{id}', [PengajuanSktmListrikControllerApi::class, 'update']);
    Route::post('/sktm-listrik', [PengajuanSktmListrikControllerApi::class, 'store']);

    // Pengajuan sktm sekolah
    Route::get('/sktm-sekolah', [PengajuanSktmSekolahControllerApi::class, 'index']);
    Route::get('/sktm-sekolah/{id}', [PengajuanSktmSekolahControllerApi::class, 'show']);
    Route::put('/sktm-sekolah/{id}', [PengajuanSktmSekolahControllerApi::class, 'update']);
    Route::post('/sktm-sekolah', [PengajuanSktmSekolahControllerApi::class, 'store']);

    // Pengajuan sk usaha
    Route::get('/sku', [PengajuanSkUsahaControllerApi::class, 'index']);
    Route::get('/sku/{id}', [PengajuanSkUsahaControllerApi::class, 'show']);
    Route::put('/sku/{id}', [PengajuanSkUsahaControllerApi::class, 'update']);
    Route::post('/sku', [PengajuanSkUsahaControllerApi::class, 'store']);

    Route::post('/client/logout', [AuthController::class, 'logout']);
});


// main pengajuan
// Route::get('/pengajuan/detail', [PengajuanControllerApi::class, 'index']);

Route::get('/pengajuan', [PengajuanControllerApi::class, 'index']);
Route::get('/pengajuan/user/{id_user}', [PengajuanControllerApi::class, 'showByUser']);
