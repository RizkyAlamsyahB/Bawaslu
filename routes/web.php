<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TpsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;
<<<<<<< HEAD
=======
use App\Http\Controllers\DataSuaraSahController;
use App\Http\Controllers\JumlahPemilihDpkController;
use App\Http\Controllers\JumlahPemilihDptController;
use App\Http\Controllers\JumlahDataPemilihController;
use App\Http\Controllers\JumlahPemilihDptbController;
use App\Http\Controllers\PenggunaHakPilihDpkController;
use App\Http\Controllers\PenggunaHakPilihDptController;
use App\Http\Controllers\PenggunaanSuratSuaraController;
use App\Http\Controllers\PenggunaHakPilihDptbController;
use App\Http\Controllers\JumlahPenggunaHakPilihController;
use App\Http\Controllers\JumlahPemilihDisabilitasController;
use App\Http\Controllers\PenggunaHakPilihDisabilitasController;
>>>>>>> ew-bawaslu

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
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('/blank-page', [HomeController::class, 'blank'])->name('blank');
});

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::middleware(['auth', 'role:super_admin'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::resource('kecamatan', KecamatanController::class);
    Route::resource('kelurahan', KelurahanController::class);
    Route::resource('tps', TpsController::class);
});

Route::middleware(['auth', 'role:kecamatan'])->group(function () {
    // Routes untuk kecamatan
});

Route::middleware(['auth', 'role:kelurahan'])->group(function () {
    // Routes untuk kelurahan
});

Route::middleware(['auth', 'role:tps'])->group(function () {
    // Routes untuk tps
<<<<<<< HEAD
=======
    Route::resource('tps', controller: JumlahPemilihDptController::class);
    Route::resource('tps', controller: JumlahPemilihDisabilitasController::class);
    Route::resource('tps', controller: JumlahPemilihDpkController::class);
    Route::resource('tps', controller: JumlahPemilihDptbController::class);
    Route::resource('tps', controller: JumlahPenggunaHakPilihController::class);
    Route::resource('tps', controller: PenggunaanSuratSuaraController::class);
    Route::resource('tps', controller: DataSuaraSahController::class);
    Route::resource('tps', controller: JumlahDataPemilihController::class);
    Route::resource('tps', controller: PenggunaHakPilihDisabilitasController::class);
    Route::resource('tps', controller: PenggunaHakPilihDpkController::class);
    Route::resource('tps', controller: PenggunaHakPilihDptbController::class);
    Route::resource('tps', controller: PenggunaHakPilihDptController::class);
>>>>>>> ew-bawaslu
});

Route::middleware(['auth', 'role:kota'])->group(function () {
    // Routes untuk kota
});

require __DIR__.'/auth.php';
