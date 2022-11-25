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

Route::get('/', function () {
    return view('main');
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

Route::resource('/kantor', KantorController::class);
Route::resource('/divisi', App\Http\Controllers\DivisiController::class);
Route::resource('/sub_divisi', App\Http\Controllers\SubdivisiController::class);
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
