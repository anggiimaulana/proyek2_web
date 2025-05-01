<?php

use App\Http\Controllers\PengajuanController;
use App\Http\Controllers\PengajuanSkBelumMenikahController;
use App\Http\Controllers\PengajuanSkPekerjaanController;
use App\Http\Controllers\PengajuanSkpotController;
use App\Http\Controllers\PengajuanSkStatusController;
use App\Http\Controllers\PengajuanSktmBeasiswaController;
use App\Http\Controllers\PengajuanSktmListrikController;
use App\Http\Controllers\PengajuanSktmSekolahController;
use App\Http\Controllers\PengajuanSkUsahaController;
use Illuminate\Support\Facades\Route;
use Vinkla\Hashids\Facades\Hashids;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk pengajuan (PDF)
Route::get('/file/preview/skbm/{id}', [PengajuanSkBelumMenikahController::class, 'exportPdf'])->name('exportPdfSkbm');
Route::get('/file/preview/skp/{id}', [PengajuanSkPekerjaanController::class, 'exportPdf'])->name('exportPdfSkp');
Route::get('/file/preview/skpot/{id}', [PengajuanSkpotController::class, 'exportPdf'])->name('exportPdfSkpot');
Route::get('/file/preview/sks/{id}', [PengajuanSkStatusController::class, 'exportPdf'])->name('exportPdfSkStatus');
Route::get('/file/preview/sku/{id}', [PengajuanSkUsahaController::class, 'exportPdf'])->name('exportPdfSkUsaha');
Route::get('/file/preview/sktm-beasiswa/{id}', [PengajuanSktmBeasiswaController::class, 'exportPdf'])->name('exportPdfSktmBeasiswa');
Route::get('/file/preview/sktm-listrik/{id}', [PengajuanSktmListrikController::class, 'exportPdf'])->name('exportPdfSktmListrik');
Route::get('/file/preview/sktm-sekolah/{id}', [PengajuanSktmSekolahController::class, 'exportPdf'])->name('exportPdfSktmSekolah');

// Route QR
Route::get('/file/scan/{hashedId}.{hashedDate}.{hashedName}', [PengajuanController::class, 'showDetail'])->name('scanView');
