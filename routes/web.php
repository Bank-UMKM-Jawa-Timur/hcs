<?php

use App\Http\Controllers\BagianController;
use App\Http\Controllers\DemosiController;
use App\Http\Controllers\JaminanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KantorController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PromosiController;
use App\Http\Controllers\TunjanganKaryawanController;
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

// Route::get('/', function () {
//     return view('login');
// });

// Route::get('home', function() {
//     return view('main');
// });

// Route::get('data_master', function() {
//     return view('data_master');
// });

// Route::get('data_table', function() {
//     return view('data_table');
// });

// Route::get('data_karyawan', function() {
//     return view('karyawan/index');
// });

// Route::get('data_karyawan/add', function() {
//     return view('karyawan/add');
// });

// Route::get('mutasi', function() {
//     return view('mutasi/index');
// }); 

// Route::get('mutasi/add', function() {
//     return view('mutasi/add');
// }); 

// Route::get('demosi', function () {
//     return view('demosi/index');
// });

// Route::get('demosi/add', function() {
//     return view('demosi/add');
// });

// Route::get('promosi', function () {
//     return view('promosi/index');
// });

// Route::get('promosi/add', function () {
//     return view('promosi/add');
// });

// Route::get('karyawan/detail', function() {
//     return view('karyawan/detail');
// });

Route::get('/', function(){
    return redirect()->route('login');
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
    Route::resource('/mutasi', \App\Http\Controllers\MutasiController::class);
    Route::resource('/demosi', DemosiController::class);
    Route::resource('/promosi', PromosiController::class);
    Route::resource('/tunjangan_karyawan', TunjanganKaryawanController::class);
    Route::resource('/bagian', BagianController::class);
    Route::resource('/laporan_bpjs', JaminanController::class);
    Route::get('/laporan-jamsostek', [JaminanController::class, 'getJamsostek'])->name('get-jamsostek');
    Route::post('/post-jamsostek', [JaminanController::class, 'postJamsostek'])->name('post-jamsostek');
    Route::get('/dpp', [JaminanController::class, 'dppIndex'])->name('index_dpp');
    Route::post('/get-dpp', [JaminanController::class, 'getDPP'])->name('get-dpp');
    
    // Routing Import excel karyawan
    Route::get('/import-karyawan', [\App\Http\Controllers\KaryawanController::class, 'import'])->name('import');
    Route::post('/upload-karyawan', [\App\Http\Controllers\KaryawanController::class, 'upload_karyawan'])->name('upload_karyawan');

    // Get komponen untuk CRUD master karyawan
    Route::get('getdivisi', [\App\Http\Controllers\KaryawanController::class, 'get_divisi']);
    Route::get('getcabang', [\App\Http\Controllers\KaryawanController::class, 'get_cabang']);
    Route::get('getsubdivisi', [\App\Http\Controllers\KaryawanController::class, 'get_subdivisi']);
    Route::get('getbagian', [KaryawanController::class, 'getbagian']);
    Route::get('deleteEditTunjangan', [KaryawanController::class, 'deleteEditTunjangan'])->name('deleteEditTunjangan');

    // Get komponen untuk mutasi
    Route::get('getdatakaryawan', [\App\Http\Controllers\MutasiController::class, 'getdatakaryawan']);
    Route::get('getdatakantor', [KaryawanController::class, 'getKantorKaryawan'])->name('getKantorKaryawan');
    Route::get('getdatapromosi', [\App\Http\Controllers\PromosiController::class, 'getdatapromosi']);

    // Get komponen untuk demosi dan promosi
    Route::get('getgolongan', [DemosiController::class, 'getgolongan']);

    // Get data untuk tunjangan karyawan
    Route::get('getdatatunjangan', [TunjanganKaryawanController::class, 'getdatatunjangan']);

    Route::get('getis', [KaryawanController::class, 'get_is']);

    Route::get('/getbagian', [KaryawanController::class, 'get_bagian']);

    Route::post('/laporan_jaminan', [JaminanController::class, 'filter'])->name('filter-laporan');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
