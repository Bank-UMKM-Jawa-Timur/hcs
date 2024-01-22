<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BagianController;
use App\Http\Controllers\PotonganController;
use App\Http\Controllers\BonusController;
use App\Http\Controllers\PtkpController;
use App\Http\Controllers\DatabaseController;
use App\Http\Controllers\DemosiController;
use App\Http\Controllers\GajiPerBulanController;
use App\Http\Controllers\HistoryJabatanController;
use App\Http\Controllers\Import\PenghasilanTeraturController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JaminanController;
use App\Http\Controllers\UangDukaController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KantorController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanPergerakanKarir\LaporanDemosiController;
use App\Http\Controllers\LaporanPergerakanKarir\LaporanMutasiController;
use App\Http\Controllers\LaporanPergerakanKarir\LaporanPromosiController;
use App\Http\Controllers\LaporanPergerakanKarir\LaporanPenonaktifanController;
use App\Http\Controllers\LaporanTetapController;
use App\Http\Controllers\LemburController;
use App\Http\Controllers\MigrasiController;
use App\Http\Controllers\MstPenambahanBrutoController;
use App\Http\Controllers\MstPenguranganBrutoController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\PejabatSementaraController;
use App\Http\Controllers\PenggantiBiayaKesehatanController;
use App\Http\Controllers\PenghasilanTidakTeraturController;
use App\Http\Controllers\PengkinianDataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProfilKantorPusatController;
use App\Http\Controllers\PromosiController;
use App\Http\Controllers\SlipGajiController;
use App\Http\Controllers\SPDController;
use App\Http\Controllers\SuratPeringatanController;
use App\Http\Controllers\THRController;
use App\Http\Controllers\TunjanganKaryawanController;
use App\Http\Controllers\RoleMasterController;
use App\Http\Controllers\UserController;
use App\Imports\ImportNpwpRekening;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Row;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});
Route::get('coming-soon',function() {
    return view('coming-soon');
});
Route::get('/dashboard', function () {
    return view('welcome');
});


Route::prefix('component')->group(function(){
    Route::get('button', function(){
       return view('components.new.button'); 
    })->name('component.button');
    Route::get('table', function(){
       return view('components.new.table'); 
    })->name('component.table');
    Route::get('form', function(){
       return view('components.new.form'); 
    })->name('component.form');
    Route::get('modal', function(){
       return view('components.new.modal'); 
    })->name('component.modal');
    Route::get('loader', function(){
       return view('components.new.loader'); 
    })->name('component.loader');
});

// Route::get('/home', [HomeController::class, 'index'])->name('home');
// Route::middleware('auth:user,karyawan')->group(function () {

// });

Route::prefix('graph')->group(function () {
    Route::get('/detail-per-cabang', [HomeController::class, 'perCabang'])->name('per-cabang');
    Route::get('/list-karyawan-by-cabang/{kd_cabang}', [HomeController::class, 'listKaryawanByCabang'])->name('list-karyawan-by-cabang');
    Route::get('/list-karyawan-by-sub-divisi/{sub_divisi}', [HomeController::class, 'listKaryawanBySubDivisi'])->name('list-karyawan-by-sub-divisi');
    Route::get('/per-divisi', [HomeController::class, 'perDivisi'])->name('per-divisi');
    Route::get('/sub-divisi/{kode}', [HomeController::class, 'subDivisi'])->name('sub-divisi');

    Route::get('/per-bagian', function () {
        return view('graph.per-bagian');
    });
    Route::get('/per-golongan', function () {
        return view('graph.per-golongan');
    });
    Route::get('/per-pendidikan', function () {
        return view('graph.per-pendidikan');
    });
    Route::get('/gaji', function () {
        return view('graph.per-gaji');
    });
    Route::get('/table-karyawan', function () {
        return view('graph.table-karyawan');
    });
    Route::get('/gaji-percabang', function () {
        return view('graph.gaji-percabang');
    });
    Route::get('/table-karyawan-sp', function () {
        return view('graph.table-karyawan-sp');
    });
});

Route::group(['middleware' => 'auth:karyawan,web'], function () {
    Route::resource('/kantor', KantorController::class);
    Route::resource('role', RoleMasterController::class);
    Route::resource('/divisi', \App\Http\Controllers\DivisiController::class);
    Route::resource('/sub_divisi', \App\Http\Controllers\SubdivisiController::class);
    Route::resource('/jabatan', \App\Http\Controllers\JabatanController::class);
    Route::resource('/cabang', \App\Http\Controllers\KantorCabangController::class);
    Route::resource('/pangkat_golongan', \App\Http\Controllers\PangkatGolonganController::class);
    Route::resource('/tunjangan', \App\Http\Controllers\TunjanganController::class);
    Route::resource('/karyawan', \App\Http\Controllers\KaryawanController::class);
    Route::get('/get-name-karyawan/{nip}', [\App\Http\Controllers\KaryawanController::class, 'getNameKaryawan']);
    Route::get('list-karyawan', [\App\Http\Controllers\KaryawanController::class, 'listKaryawan']);
    Route::resource('/mutasi', \App\Http\Controllers\MutasiController::class);
    Route::resource('/umur', \App\Http\Controllers\UmurController::class);
    Route::resource('/demosi', DemosiController::class);
    Route::resource('/promosi', PromosiController::class);
    Route::resource('/tunjangan_karyawan', TunjanganKaryawanController::class);
    Route::resource('/bagian', BagianController::class);
    Route::resource('/pajak_penghasilan', PenghasilanTidakTeraturController::class);
    Route::resource('/user', UserController::class);
    Route::get('/reset-pass/{id}', [UserController::class, 'resetPass'])->name('resetPass');
    Route::post('/reset-password/{id}', [UserController::class, 'updatePass'])->name("updatePass");
    Route::get('/session-list', [AuthenticatedSessionController::class, 'index'])->name("session-list");

    Route::resource('/potongan', PotonganController::class);
    Route::post('/get-karyawan-by-nip', [PotonganController::class, 'getKaryawanByNip'])->name('karyawan-by-entitas');
    Route::get('import-potongan', [\App\Http\Controllers\PotonganController::class, 'importPotongan'])->name('import-potongan');
    Route::get('/potongan-template-excel', [PotonganController::class, 'templateExcel'])->name('template-excel-potongan');
    Route::post('import-potongan-post', [\App\Http\Controllers\PotonganController::class, 'importPotonganPost'])->name('import-potongan-post');
    Route::get('/detail/{bulan}/{tahun}', [PotonganController::class, 'detail'])->name('detail-potongan');

    Route::prefix('penghasilan')->name('penghasilan.')->group(function () {
        Route::resource('import-penghasilan-teratur', \App\Http\Controllers\Import\PenghasilanTeraturController::class);
        Route::post('/get-karyawan-by-entitas', [PenghasilanTeraturController::class, 'getKaryawanByEntitas'])->name('karyawan-by-entitas');
        Route::get('/get-karyawan-search', [PenghasilanTeraturController::class, 'getKaryawanSearch'])->name('karyawan-search');
        Route::get('/details/{idTunjangan}', [PenghasilanTeraturController::class, 'details'])->name('details');
        Route::post('/cetak-vitamin', [PenghasilanTeraturController::class, 'cetakVitamin'])->name('cetak-vitamin');
        Route::get('/template-excel', [PenghasilanTeraturController::class, 'templateExcel'])->name('template-excel');
        Route::get('/lock', [PenghasilanTeraturController::class, 'lock'])->name('lock');
        Route::get('/unlock', [PenghasilanTeraturController::class, 'unlock'])->name('unlock');
        Route::get('/edit-tunjangan', [PenghasilanTeraturController::class, 'editTunjangan'])->name('edit-tunjangan');
        Route::post('/edit-tunjangan-post', [PenghasilanTeraturController::class, 'editTunjanganPost'])->name('edit-tunjangan-post');
    });
    Route::post('upload-penghasilan',[GajiPerBulanController::class,'upload'])->name('upload.penghasilanPerBulan');
    Route::get('cetak-penghasilan/{id}',[GajiPerBulanController::class,'cetak'])->name('cetak.penghasilanPerBulan');
    Route::get('update-tanggal-cetak/{id}',[GajiPerBulanController::class,'updateTanggalCetak'])->name('cetak.updateTanggalCetak');
    Route::resource('/gaji_perbulan', GajiPerBulanController::class);
    Route::get('/get-data-penghasilan-json', [GajiPerbulanController::class, 'getDataPenghasilanJson'])->name('gaji_perbulan.get_data_penghasilan_json');
    Route::get('/penyesuaian-gaji-json', [GajiPerbulanController::class, 'penyesuaianDataJson'])->name('gaji_perbulan.penyesuian_json');
    Route::post('/penyesuaian-gaji-json', [GajiPerbulanController::class, 'prosesFinal'])->name('gaji_perbulan.proses_final');
    Route::get('/penghasilan-kantor', [GajiPerbulanController::class, 'penghasilanKantor'])->name('gaji_perbulan.penghasilan_kantor');
    Route::resource('/pengganti-biaya-kesehatan', PenggantiBiayaKesehatanController::class);
    Route::resource('/uang-duka', UangDukaController::class);
    Route::resource('/backup', BackupController::class);
    Route::resource('/history_jabatan', HistoryJabatanController::class);
    Route::resource('/gaji', SlipGajiController::class);
    Route::resource('/pengkinian_data', PengkinianDataController::class);
    Route::resource('/ptkp', PtkpController::class);
    Route::resource('/penambahan-bruto', MstPenambahanBrutoController::class);
    Route::resource('/pengurangan-bruto', MstPenguranganBrutoController::class);
    Route::resource('/lembur', LemburController::class);
    Route::resource('/spd', SPDController::class);
    Route::resource('/laporan-rekapitulasi', LaporanTetapController::class);

    // Bonus Data
    Route::get('bonus/import-data', [BonusController::class, 'import'])->name('bonus.import-data');
    Route::get('bonus/excel', [BonusController::class, 'fileExcel'])->name('bonus.excel');
    Route::get('bonus/detail/{id}', [BonusController::class, 'detail'])->name('bonus.detail');

    Route::resource('bonus', BonusController::class);
    Route::resource('/thr', THRController::class);
    Route::get('/profil-kantor-pusat', [ProfilKantorPusatController::class, 'index'])->name('profil-kantor-pusat.index');
    Route::post('/profil-kantor-pusat', [ProfilKantorPusatController::class, 'update'])->name('profil-kantor-pusat.update');
    Route::get('/bonus-lock', [BonusController::class, 'lock'])->name('bonus-lock');
    Route::get('/bonus-unlock', [BonusController::class, 'unlock'])->name('bonus-unlock');
    Route::get('/edit-tunjangan-bonus/{idTunjangan}', [BonusController::class, 'editTunjangan'])->name('edit-tunjangan-bonus');
    Route::post('/edit-tunjangan-bonus/post', [BonusController::class, 'editTunjanganPost'])->name('edit-tunjangan-bonus-post');

    Route::prefix('penghasilan-tidak-teratur')
        ->name('penghasilan-tidak-teratur.')
        ->controller(PenghasilanTidakTeraturController::class)
        ->group(function () {
            Route::get('/', 'lists')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/store', 'store')->name('store');
            Route::get('/detail/{idTunjangan}', 'show')->name('detail');
            Route::get('/input-tidak-teratur', 'createTidakTeratur')->name('input-tidak-teratur');
            Route::get('template-tidak-teratur', 'templateTidakTeratur')->name('templateTidakTeratur');
            Route::get('template-biaya-kesehatan', 'templateBiayaKesehatan')->name('templateBiayaKesehatan');
            Route::get('template-uang-duka', 'templateBiayaDuka')->name('templateBiayaDuka');
            Route::get('/lock', 'lock')->name('lock');
            Route::get('/unlock', 'unlock')->name('unlock');
            Route::get('/edit-tunjangan/{idTunjangan}/{tanggal}/{kdEntitas}', 'editTunjangan')->name('edit-tunjangan-tidak-teratur');
            Route::post('/edit-tunjangan/post', 'editTunjanganPost')->name('edit-tunjangan-tidak-teratur-post');
            Route::get('validasi-insert', 'validasiInsert')->name('validasi-insert');
        });

    // Penonaktifan Karyawan
    Route::prefix('penonaktifan')
        ->name('penonaktifan.')
        ->controller(KaryawanController::class)
        ->group(function () {
            Route::get('/', 'indexPenonaktifan')->name('index');
            Route::get('/add', 'penonaktifanAdd')->name('create');
            Route::post('/store', 'penonaktifan')->name('store');
        });

    // Surat Peringatan
    Route::get('surat-peringatan/history', [SuratPeringatanController::class, 'history'])
        ->name('surat-peringatan.history');
    Route::resource('surat-peringatan', SuratPeringatanController::class)
        ->except('destroy');

    // Database
    Route::prefix('manage-database')
        ->name('database.')
        ->controller(DatabaseController::class)
        ->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('restore/{id}', 'restore')->name('restore');
            Route::get('rollback/{id}', 'rollback')->name('rollback');
            Route::post('checkout', 'checkout')->name('checkout');
        });

    // Pejabat Sementara
    Route::match(['GET', 'POST'], 'pejabat-sementara/history', [PejabatSementaraController::class, 'history'])
        ->name('pejabat-sementara.history');
    Route::resource('pejabat-sementara', PejabatSementaraController::class);

    Route::resource('/laporan_jamsostek', JaminanController::class);
    Route::get('/laporan-jamsostek', [JaminanController::class, 'getJamsostek'])->name('get-jamsostek');
    Route::post('/post-jamsostek', [JaminanController::class, 'postJamsostek'])->name('post-jamsostek');
    Route::get('/dpp', [JaminanController::class, 'dppIndex'])->name('index_dpp');
    Route::post('/dpp', [JaminanController::class, 'getDpp'])->name('get-dpp');
    Route::post('penghasilan/get-gaji', [PenghasilanTidakTeraturController::class, 'filter'])->name('get-penghasilan');
    Route::get('/getPenghasilan', [PenghasilanTidakTeraturController::class, 'getPenghasilan'])->name('getPenghasilanResult');
    Route::get('/list-penghasilan-tidak-teratur', [PenghasilanTidakTeraturController::class, 'lists'])->name('list-penghasilan-tidak-teratur');

    // Klasifikasi Data Karyawan
    Route::post('/klasifikasi_data', [App\Http\Controllers\KlasifikasiController::class, 'klasifikasi_data'])->name('klasifikasi-data');
    Route::get('/klasifikasi-karyawan', [App\Http\Controllers\KlasifikasiController::class, 'index'])->name('klasifikasi_karyawan');

    // Routing Import excel karyawan
    Route::get('/import-karyawan', [\App\Http\Controllers\KaryawanController::class, 'import'])->name('import');
    Route::post('/upload-karyawan', [\App\Http\Controllers\KaryawanController::class, 'upload_karyawan'])->name('upload_karyawan');


    // Get komponen untuk CRUD master karyawan
    Route::get('getdivisi', [\App\Http\Controllers\KaryawanController::class, 'get_divisi'])->name('get_divisi');
    Route::get('getcabang', [\App\Http\Controllers\KaryawanController::class, 'get_cabang'])->name('get_cabang');
    Route::get('getsubdivisi', [\App\Http\Controllers\KaryawanController::class, 'get_subdivisi'])->name('get_subdivisi');
    Route::get('getbagian', [KaryawanController::class, 'getbagian'])->name('get_bagian');
    Route::get('deleteEditTunjangan', [KaryawanController::class, 'deleteEditTunjangan'])->name('deleteEditTunjangan');

    // Get komponen untuk mutasi
    Route::get('getdatakaryawan', [\App\Http\Controllers\MutasiController::class, 'getdatakaryawan'])->name('getDataKaryawan');
    Route::get('getdatakantor', [KaryawanController::class, 'getKantorKaryawan'])->name('getKantorKaryawan');
    Route::get('getdatapromosi', [\App\Http\Controllers\PromosiController::class, 'getdatapromosi'])->name('getDataPromosi');

    // Get komponen untuk demosi dan promosi
    Route::get('getgolongan', [DemosiController::class, 'getgolongan'])->name('getGolongan');
    Route::get('getDataGjPromosi', [PromosiController::class, 'getDataGajiPromosi'])->name('getDataGjPromosi');

    // Get data untuk tunjangan karyawan
    Route::get('getdatatunjangan', [TunjanganKaryawanController::class, 'getdatatunjangan'])->name('getDataTunjangan');

    Route::get('getis', [KaryawanController::class, 'get_is'])->name('getIs');

    Route::get('/getbagian', [KaryawanController::class, 'get_bagian'])->name('getBagian');

    Route::post('/laporan_jaminan', [JaminanController::class, 'filter'])->name('filter-laporan');

    Route::post('/upload_penghasilan', [PenghasilanTidakTeraturController::class, 'upload'])->name('upload_penghasilan');
    Route::get('/import-penghasilan', [PenghasilanTidakTeraturController::class, 'import'])->name('import-penghasilan-index');
    Route::post('/insert-penghasilan', [PenghasilanTidakTeraturController::class, 'insertPenghasilan'])->name('insert-penghasilan');
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

    // Reset Password Karyawan
    Route::post('reset-password-karyawan', [KaryawanController::class, 'resetPasswordKaryawan'])->name('reset-password-karyawan');


    // Get Laporan Gaji
    Route::post('/laporan_gaji/getLaporan', [SlipGajiController::class, 'getLaporan'])->name('getLaporanGaji');

    // SlipJurnal
    Route::get('/slip_jurnal', [SlipGajiController::class, 'slipJurnalIndex'])->name('slipIndex');
    Route::post('/slip_jurnal/getSlip', [SlipGajiController::class, 'slipJurnal'])->name('getSlip');

    // Slip gaji
    Route::prefix('slip')->name('slip.')->group(function() {
        Route::get('', [SlipGajiController::class, 'slip'])->name('index');
        Route::get('/cetak-slip', [SlipGajiController::class, 'cetakSlip'])->name('cetak_slip');
        Route::get('/slip/pdf', [SlipGajiController::class, 'slipPDF'])->name('slip.pdf');
    });

    Route::prefix('/migrasi')->group(function () {
        Route::get('/jabatan', [MigrasiController::class, 'migrasiJabatan'])->name('migrasiJabatan');
        Route::get('/pjs', [MigrasiController::class, 'migrasiPJS'])->name('migrasiPJS');
        Route::get('/sp', [MigrasiController::class, 'migrasiSP'])->name('migrasiSP');
        Route::post('/store', [MigrasiController::class, 'store'])->name('migrasiStore');
    });

    Route::get('/get-data-karyawan-by-nip', [PengkinianDataController::class, 'getDataKaryawanByNIP'])->name('get-data-karyawan-by-nip');

    Route::get('/import_pengkinian', [PengkinianDataController::class, 'pengkinian_data_index'])->name('pengkinian-data-import-index');
    Route::post('/post-import-pengkinian', [PengkinianDataController::class, 'postPengkinianImport'])->name('post-pengkinian-import');

    Route::get('/import-data_keluarga', [KaryawanController::class, 'importKeluargaIndex']);
    Route::post('import-keluarga-post', [KaryawanController::class, 'importKeluarga'])->name('import-data_keluarga');

    // Reminder Pensiun
    Route::get('/reminder_pensiun', [KaryawanController::class, 'reminderPensiunIndex'])->name('reminder-pensiun.index');
    // Route::post('/reminder_pensiun-show', [KaryawanController::class, 'reminderPensiunShow'])->name('reminder-pensiun.show');
    Route::get('/reminder_pensiun-show', [KaryawanController::class, 'reminderPensiunShow'])->name('reminder-pensiun.show');

    // Export CV
    Route::get('/export-cv/{id}', [KaryawanController::class, 'exportCV'])->name('export-cv');

    Route::prefix('laporan-pergerakan-karir')->group(function () {
        Route::get('laporan-mutasi', [LaporanMutasiController::class, 'index'])->name('laporan-mutasi.index');
        Route::get('laporan-demosi', [LaporanDemosiController::class, 'index'])->name('laporan-demosi.index');
        Route::get('laporan-promosi', [LaporanPromosiController::class, 'index'])->name('laporan-promosi.index');
        Route::get('laporan-penonaktifan', [LaporanPenonaktifanController::class, 'index'])->name('laporan-penonaktifan.index');
    });

    Route::get('/import-pph', function () {
        return view('gaji_perbulan.import');
    });
    Route::post('post-import-pph', [GajiPerBulanController::class, 'importPPH'])->name('import-pph');

    Route::prefix('payroll')
        ->name('payroll.')
        ->group(function () {
            Route::get('/', [PayrollController::class, 'index'])->name('index');
            Route::get('pdf', [PayrollController::class, 'cetak'])->name('pdf');
            Route::get('/cetak-slip', [PayrollController::class, 'cetakSlip'])->name('cetak_slip');
            Route::get('/slip', [PayrollController::class, 'slip'])->name('slip');
            Route::get('/slip/pdf', [PayrollController::class, 'slipPDF'])->name('slip.pdf');
        });

    Route::get('get-rincian-payroll', [GajiPerBulanController::class, 'getRincianPayroll'])->name('get-rincian-payroll');
    Route::get('get-lampiran-gaji/{id}', [GajiPerBulanController::class, 'getLampiranGaji'])->name('get-lampiran-gaji');
    Route::get('/proses-gaji-download-rincian', [GajiPerBulanController::class, 'downloadRincianPayroll'])->name('proses-gaji-download-rincian');

    Route::get('/download-rekapitulasi', [LaporanTetapController::class, 'cetak'])->name('download-rekapitulasi');

    // Route update status ptkp
    Route::get('/import-status-ptkp', function(){
        return view('karyawan.import.status-ptkp');
    });
    Route::post('/post-import-status-ptkp', [KaryawanController::class, 'importStatusPtkp'])->name('import-status-ptkp');
});
require __DIR__.'/auth.php';
