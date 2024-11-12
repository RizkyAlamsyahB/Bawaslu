<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TpsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WizardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
use App\Http\Controllers\RekapitulasiController;
use App\Http\Controllers\PasanganCalonController;
use App\Http\Controllers\TipePemilihanController;
use App\Http\Controllers\JumlahDataPemilihController;

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
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('kecamatan', KecamatanController::class);
    Route::resource('kelurahan', KelurahanController::class);
    Route::resource('tps', TpsController::class);

    // Route khusus untuk import
    Route::post('kelurahan/import', [KelurahanController::class, 'import'])->name('kelurahan.import');
    Route::post('tps/import', [TpsController::class, 'import'])->name('tps.import');
    Route::post('kecamatan/import', [KecamatanController::class, 'import'])->name('kecamatan.import');

    Route::get('/kelurahan/by-kecamatan/{kecamatan_id}', [KelurahanController::class, 'getKelurahanByKecamatan'])->name('kelurahan.by-kecamatan');

    // Rute untuk mengambil TPS berdasarkan Kelurahan
    Route::get('get-tps-by-kelurahan/{kelurahan_id}', [TpsController::class, 'getTpsByKelurahan']);
    // Rute untuk mengambil TPS berdasarkan KEcamatan
    Route::get('/tps/by-kecamatan/{kecamatan_id}', [TpsController::class, 'getTpsByKecamatan'])->name('tps.by-kecamatan');

    Route::resource('user', UserController::class);
    Route::get('/kelurahan/by-kecamatan/{kecamatan_id}', [UserController::class, 'getKelurahanByKecamatan']);
    Route::get('/tps/by-kelurahan/{kelurahan_id}', [UserController::class, 'getTpsByKelurahan']);

    Route::resource('tipe_pemilihan', TipePemilihanController::class);

    Route::resource('pasangan_calon', PasanganCalonController::class);
});

Route::middleware(['auth', 'role:kecamatan'])->group(function () {
    // Routes untuk kecamatan
});

Route::middleware(['auth', 'role:kelurahan'])->group(function () {
    // Routes untuk kelurahan
});

Route::middleware(['auth', 'role:tps,super_admin'])->group(function () {
    Route::resource('jumlah_data_pemilih', JumlahDataPemilihController::class);
    Route::get('/rekapitulasi/{tipe_pemilihan_id}', [RekapitulasiController::class, 'create'])->name('rekapitulasi.create');

    Route::get('/gubernur/wizard', [WizardController::class, 'create'])->name('wizard.gubernur');
    Route::get('/walikota/wizard', [WizardController::class, 'create'])->name('wizard.walikota');
    Route::post('/wizard/store', [WizardController::class, 'store'])->name('wizard.store');
});

Route::middleware(['auth', 'role:kota'])->group(function () {
    // Routes untuk kota
});

require __DIR__ . '/auth.php';
