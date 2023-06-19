<?php

use App\Http\Controllers\Api\KaryawanController as ApiKaryawanController;
use App\Http\Controllers\Api\Select2\KaryawanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
});
Route::get('api/v1/karyawan/{nip}', [KaryawanController::class, 'getDetail'])->name('karyawan.detail');
