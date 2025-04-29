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

// Pengajuan skbm
Route::get('/skbm', [PengajuanSkBelumMenikahControllerApi::class, 'index']);
Route::get('/skbm/{id}', [PengajuanSkBelumMenikahControllerApi::class, 'show']);
Route::post('/skbm', [PengajuanSkBelumMenikahControllerApi::class, 'store']);
Route::put('/skbm/{id}', [PengajuanSkBelumMenikahControllerApi::class, 'update']);
Route::delete('/skbm/{id}', [PengajuanSkBelumMenikahControllerApi::class, 'destroy']);

// Pengajuan skp
Route::get('/skp', [PengajuanSkPekerjaanControllerApi::class, 'index']);
Route::get('/skp/{id}', [PengajuanSkPekerjaanControllerApi::class, 'show']);
Route::post('/skp', [PengajuanSkPekerjaanControllerApi::class, 'store']);
Route::put('/skp/{id}', [PengajuanSkPekerjaanControllerApi::class, 'update']);
Route::delete('/skp/{id}', [PengajuanSkPekerjaanControllerApi::class, 'destroy']);

// Pengajuan skpot
Route::get('/skpot', [PengajuanSkpotBeasiswaControllerApi::class, 'index']);
Route::get('/skpot/{id}', [PengajuanSkpotBeasiswaControllerApi::class, 'show']);
Route::post('/skpot', [PengajuanSkpotBeasiswaControllerApi::class, 'store']);
Route::put('/skpot/{id}', [PengajuanSkpotBeasiswaControllerApi::class, 'update']);
Route::delete('/skpot/{id}', [PengajuanSkpotBeasiswaControllerApi::class, 'destroy']);

// Pengajuan skp bantuan
Route::get('/skp_bantuan', [PengajuanSkpPengajuanBantuanControllerApi::class, 'index']);
Route::get('/skp_bantuan/{id}', [PengajuanSkpPengajuanBantuanControllerApi::class, 'show']);
Route::post('/skp_bantuan', [PengajuanSkpPengajuanBantuanControllerApi::class, 'store']);
Route::put('/skp_bantuan/{id}', [PengajuanSkpPengajuanBantuanControllerApi::class, 'update']);
Route::delete('/skp_bantuan/{id}', [PengajuanSkpPengajuanBantuanControllerApi::class, 'destroy']);

// Pengajuan sks
Route::get('/sks', [PengajuanSkStatusControllerApi::class, 'index']);
Route::get('/sks/{id}', [PengajuanSkStatusControllerApi::class, 'show']);
Route::post('/sks', [PengajuanSkStatusControllerApi::class, 'store']);
Route::put('/sks/{id}', [PengajuanSkStatusControllerApi::class, 'update']);
Route::delete('/sks/{id}', [PengajuanSkStatusControllerApi::class, 'destroy']);

// Pengajuan sktm beasiswa
Route::get('/sktm_beasiswa', [PengajuanSktmBeasiswaControllerApi::class, 'index']);
Route::get('/sktm_beasiswa/{id}', [PengajuanSktmBeasiswaControllerApi::class, 'show']);
Route::post('/sktm_beasiswa', [PengajuanSktmBeasiswaControllerApi::class, 'store']);
Route::put('/sktm_beasiswa/{id}', [PengajuanSktmBeasiswaControllerApi::class, 'update']);
Route::delete('/sktm_beasiswa/{id}', [PengajuanSktmBeasiswaControllerApi::class, 'destroy']);

// Pengajuan sktm listrik
Route::get('/sktm_listrik', [PengajuanSktmListrikControllerApi::class, 'index']);
Route::get('/sktm_listrik/{id}', [PengajuanSktmListrikControllerApi::class, 'show']);
Route::post('/sktm_listrik', [PengajuanSktmListrikControllerApi::class, 'store']);
Route::put('/sktm_listrik/{id}', [PengajuanSktmListrikControllerApi::class, 'update']);
Route::delete('/sktm_listrik/{id}', [PengajuanSktmListrikControllerApi::class, 'destroy']);

// Pengajuan sktm sekolah
Route::get('/sktm_sekolah', [PengajuanSktmSekolahControllerApi::class, 'index']);
Route::get('/sktm_sekolah/{id}', [PengajuanSktmSekolahControllerApi::class, 'show']);
Route::post('/sktm_sekolah', [PengajuanSktmSekolahControllerApi::class, 'store']);
Route::put('/sktm_sekolah/{id}', [PengajuanSktmSekolahControllerApi::class, 'update']);
Route::delete('/sktm_sekolah/{id}', [PengajuanSktmSekolahControllerApi::class, 'destroy']);

// Pengajuan sk usaha
Route::get('/sku', [PengajuanSkUsahaControllerApi::class, 'index']);
Route::get('/sku/{id}', [PengajuanSkUsahaControllerApi::class, 'show']);
Route::post('/sku', [PengajuanSkUsahaControllerApi::class, 'store']);
Route::put('/sku/{id}', [PengajuanSkUsahaControllerApi::class, 'update']);
Route::delete('/sku/{id}', [PengajuanSkUsahaControllerApi::class, 'destroy']);
