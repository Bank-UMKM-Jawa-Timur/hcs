<?php

use App\Http\Controllers\Api\KaryawanController as ApiKaryawanController;
use App\Http\Controllers\Api\ProfilKantorController;
use App\Http\Controllers\Api\Select2\KaryawanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Row;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('select2')->name('api.select2.')->group(function () {
    Route::controller(KaryawanController::class)->group(function () {
        Route::get('employees', 'karyawan')->name('karyawan');
        Route::get('employees/pjs', 'karyawanPjs')->name('karyawan.pjs');
    });
});

Route::name('api.')->group(function () {
    Route::get('karyawan', ApiKaryawanController::class)->name('karyawan');
    Route::get('get-karyawan',[ApiKaryawanController::class,'getKaryawan'])->name('get.karyawan');
    Route::get('get-autocomplete',[ApiKaryawanController::class,'autocomplete'])->name('get.autocomplete');
});

Route::prefix('v1')->group(function() {
    Route::get('karyawan/{nip}', [KaryawanController::class, 'getDetail'])->name('karyawan.detail');
    Route::prefix('cabang')->group(function() {
        Route::get('list', [ProfilKantorController::class, 'list']);
        Route::get('/{kode}', [ProfilKantorController::class, 'getByKode']);
    });
});
