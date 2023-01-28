<?php

// use App\Http\Controllers\BackupController;
use App\Http\Controllers\BagianController;
use App\Http\Controllers\DemosiController;
use App\Http\Controllers\GajiPerBulanController;
use App\Http\Controllers\JaminanController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KantorController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PenghasilanTidakTeraturController;
use App\Http\Controllers\PromosiController;
use App\Http\Controllers\SuratPeringatanController;
use App\Http\Controllers\TunjanganKaryawanController;
use App\Imports\ImportNpwpRekening;
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

// Route::get('penghasilan', function() {
//     return view('penghasilan/index');
// });

// Route::get('penghasilan/add', function() {
//     return view('penghasilan/add');
// });

// Route::get('penghasilan/gajipajak', function() {
//     return view('penghasilan/gajipajak');
// });

// Route::get('umur/add', function() {
//     return view('umur/add');
// });

// Route::get('umur', function () {
//     return view('umur/add');
// });


// Route::get('karyawan/klasifikasi', function() {
//     return view('karyawan/klasifikasi');
// });


Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::resource('/kantor', KantorController::class);
    Route::resource('/divisi', \App\Http\Controllers\DivisiController::class);
    Route::resource('/sub_divisi', \App\Http\Controllers\SubdivisiController::class);
    Route::resource('/jabatan', \App\Http\Controllers\JabatanController::class);
    Route::resource('/cabang', \App\Http\Controllers\KantorCabangController::class);
    Route::resource('/pangkat_golongan', \App\Http\Controllers\PangkatGolonganController::class);
    Route::resource('/tunjangan', \App\Http\Controllers\TunjanganController::class);
    Route::resource('/karyawan', \App\Http\Controllers\KaryawanController::class);
    Route::resource('/mutasi', \App\Http\Controllers\MutasiController::class);
    Route::resource('/umur', \App\Http\Controllers\UmurController::class);
    Route::resource('/demosi', DemosiController::class);
    Route::resource('/promosi', PromosiController::class);
    Route::resource('/tunjangan_karyawan', TunjanganKaryawanController::class);
    Route::resource('/bagian', BagianController::class);
    Route::resource('/penghasilan', PenghasilanTidakTeraturController::class);
    Route::resource('/gaji_perbulan', GajiPerBulanController::class);
    // Route::resource('/backup', BackupController::class);

    // Penonaktifan Karyawan
    Route::controller(KaryawanController::class)->group(function () {
        Route::match(['get', 'post'], 'penonaktifan', 'penonaktifan')->name('karyawan.penonaktifan');
    });

    // Surat Peringatan
    Route::resource('surat-peringatan', SuratPeringatanController::class)
        ->except('destroy');

    Route::resource('/laporan_jamsostek', JaminanController::class);
    Route::get('/laporan-jamsostek', [JaminanController::class, 'getJamsostek'])->name('get-jamsostek');
    Route::post('/post-jamsostek', [JaminanController::class, 'postJamsostek'])->name('post-jamsostek');
    Route::get('/dpp', [JaminanController::class, 'dppIndex'])->name('index_dpp');
    Route::post('/dpp', [JaminanController::class, 'getDpp'])->name('get-dpp');
    Route::post('penghasilan/get-gaji', [PenghasilanTidakTeraturController::class, 'filter'])->name('get-penghasilan');
    Route::get('/getPenghasilan', [PenghasilanTidakTeraturController::class, 'getPenghasilan'])->name('getPenghasilanResult');
    Route::post('/klasifikasi_data', [KaryawanController::class, 'klasifikasi_data'])->name('klasifikasi-data');;

    // Routing Import excel karyawan
    Route::get('/import-karyawan', [\App\Http\Controllers\KaryawanController::class, 'import'])->name('import');
    Route::post('/upload-karyawan', [\App\Http\Controllers\KaryawanController::class, 'upload_karyawan'])->name('upload_karyawan');

    Route::get('/klasifikasi-karyawan', [\App\Http\Controllers\KaryawanController::class, 'klasifikasi'])->name('klasifikasi_karyawan');

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
    Route::get('getDataGjPromosi', [PromosiController::class, 'getDataGajiPromosi'])->name('getDataGjPromosi');

    // Get data untuk tunjangan karyawan
    Route::get('getdatatunjangan', [TunjanganKaryawanController::class, 'getdatatunjangan']);

    Route::get('getis', [KaryawanController::class, 'get_is']);

    Route::get('/getbagian', [KaryawanController::class, 'get_bagian']);

    Route::post('/laporan_jaminan', [JaminanController::class, 'filter'])->name('filter-laporan');

    Route::post('/upload_penghasilan', [PenghasilanTidakTeraturController::class, 'upload'])->name('upload_penghasilan');
    Route::get('/getKaryawanByNama', [PenghasilanTidakTeraturController::class, 'cariNama'])->name('getKaryawanByNama');
    Route::get('/getKaryawanByNip', [\App\Http\Controllers\PenghasilanTidakTeraturController::class, 'getDataKaryawan'])->name('getKaryawanByNip');
    Route::post('/laporan_jamsostek', [JaminanController::class, 'filter'])->name('filter-laporan');
    Route::get('/getBulan', [GajiPerBulanController::class, 'getBulan'])->name('getBulan');

    // Import update Tunjangan
    Route::get('/update-tunjangan', [KaryawanController::class, 'import_tunjangan'])->name('import-tunjangan');
    Route::post('/update_tunjangan', [KaryawanController::class, 'update_tunjangan'])->name('update_tunjangan');

    // Import Npwp dan Norek
    Route::get('/import_npwp', [KaryawanController::class, 'importNpwpRekeningIndex'])->name('import-npwp-index');
    Route::post('/import_npwp-rekening', [KaryawanController::class, 'importNpwpRekening'])->name('import-npwp');

    // Import Status
    Route::get('import_update_status', [KaryawanController::class, 'importStatusIndex'])->name('import-status-index');
    Route::post('import_update-status', [KaryawanController::class, 'importStatus'])->name('import_status');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
