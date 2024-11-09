<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TpsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\KecamatanController;
use App\Http\Controllers\KelurahanController;

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

    // di routes/web.php
    Route::get('/kelurahan/by-kecamatan/{kecamatan_id}', [KelurahanController::class, 'getKelurahanByKecamatan'])->name('kelurahan.by-kecamatan');

    // Rute untuk mengambil TPS berdasarkan Kelurahan
    Route::get('get-tps-by-kelurahan/{kelurahan_id}', [TpsController::class, 'getTpsByKelurahan']);
    // Rute untuk mengambil TPS berdasarkan KEcamatan
    Route::get('/tps/by-kecamatan/{kecamatan_id}', [TpsController::class, 'getTpsByKecamatan'])->name('tps.by-kecamatan');

    Route::resource('user', UserController::class);
});

Route::middleware(['auth', 'role:kecamatan'])->group(function () {
    // Routes untuk kecamatan
});

Route::middleware(['auth', 'role:kelurahan'])->group(function () {
    // Routes untuk kelurahan
});

Route::middleware(['auth', 'role:tps'])->group(function () {
    // Routes untuk tps
});

Route::middleware(['auth', 'role:kota'])->group(function () {
    // Routes untuk kota
});

require __DIR__ . '/auth.php';
