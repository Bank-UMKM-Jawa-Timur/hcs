<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KantorController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/login_page', function () {
    return view('login');
});

Route::get('home', function() {
    return view('main');
});

Route::get('data_master', function() {
    return view('data_master');
});

Route::get('data_table', function() {
    return view('data_table');
});

Route::get('data_karyawan', function() {
    return view('karyawan/index');
});

Route::get('data_karyawan/add', function() {
    return view('karyawan/add');
});

Route::get('mutasi', function() {
    return view('mutasi/index');
}); 

Route::get('mutasi/add', function() {
    return view('mutasi/add');
}); 

Route::get('demosi', function () {
    return view('demosi/index');
});

Route::get('demosi/add', function() {
    return view('demosi/add');
});

Route::get('promosi', function () {
    return view('promosi/index');
});

Route::get('promosi/add', function () {
    return view('promosi/add');
});

Route::get('/', function(){
    return view('data_table');
});

Route::group(['middleware' => 'auth'], function(){
    Route::resource('/kantor', KantorController::class);
    Route::resource('/divisi', App\Http\Controllers\DivisiController::class);
    Route::resource('/sub_divisi', App\Http\Controllers\SubdivisiController::class);
    Route::resource('/jabatan', App\Http\Controllers\JabatanController::class);
    Route::resource('/cabang', \App\Http\Controllers\KantorCabangController::class);
    Route::resource('/pangkat_golongan', \App\Http\Controllers\PangkatGolonganController::class);
    Route::resource('/tunjangan', \App\Http\Controllers\TunjanganController::class);
    Route::resource('/karyawan', \App\Http\Controllers\KaryawanController::class);
    
    Route::get('/import-karyawan', [\App\Http\Controllers\KaryawanController::class, 'import'])->name('import');
    Route::post('/upload-karyawan', [\App\Http\Controllers\KaryawanController::class, 'upload_karyawan'])->name('upload_karyawan');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
