<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManWeb\UsersController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ManWeb\StrukturAoController;
use App\Http\Controllers\Produksi\ProduksiController;
use App\Http\Controllers\Penerbitan\{PenulisController, NaskahController, PenilaianNaskahController, TimelineController};

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

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('do-login', [AuthController::class, 'doLogin']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware(['auth'])->group(function() {
    Route::post('notification', [NotificationController::class, 'index']);
    Route::get('/', [HomeController::class, 'index']);
    Route::post('import-db', [HomeController::class, 'importDB']);

    /* Home */
    Route::post('home/test', [HomeController::class, 'ajaxTest']);
    Route::post('home/penerbitan/{cat}', [HomeController::class, 'ajaxPenerbitan']);
    Route::post('public/penerbitan/fullcalendar/{cat}', [NaskahController::class, 'ajaxFromCalendar']);

    Route::get('penerbitan/penulis', [PenulisController::class, 'index']);
    Route::match(['get', 'post'], 'penerbitan/penulis/membuat-penulis', [PenulisController::class, 'createPenulis']);
    Route::match(['get', 'post'], 'penerbitan/penulis/mengubah-penulis/{id}', [PenulisController::class, 'updatePenulis']);
    Route::match(['get', 'post'], 'penerbitan/penulis/detail-penulis/{id}', [PenulisController::class, 'detailPenulis']);
    Route::post('penerbitan/penulis/hapus-penulis', [PenulisController::class, 'deletePenulis']);

    Route::get('penerbitan/naskah', [NaskahController::class, 'index']);
    Route::get('penerbitan/naskah/ajax-call-page/{page}', [NaskahController::class, 'ajaxCallPage']);
    Route::match(['get', 'post'], 'penerbitan/naskah/melihat-naskah/{id}', [NaskahController::class, 'viewNaskah']);
    Route::match(['get', 'post'], 'penerbitan/naskah/membuat-naskah', [NaskahController::class, 'createNaskah']);
    Route::match(['get', 'post'], 'penerbitan/naskah/mengubah-naskah/{id}', [NaskahController::class, 'updateNaskah']);
    Route::post('penerbitan/naskah/penilaian/{cat}', [PenilaianNaskahController::class, 'index']);
    Route::post('penerbitan/naskah/timeline/{cat}', [TimelineController::class, 'index']);

    Route::get('manajemen-web/users', [UsersController::class, 'index']);
    Route::match(['get', 'post'], 'manajemen-web/user/ajax/{act}/{id?}', [UsersController::class, 'ajaxUser']);
    Route::get('manajemen-web/user/{id}', [UsersController::class, 'selectUser']);
    Route::post('manajemen-web/user/update-access', [UsersController::class, 'updateAccess']);

    /* Menu Struktur Organisasi [cabang, divisi, jabatan] */
    Route::get('manajemen-web/struktur-ao', [StrukturAoController::class, 'index']);
    Route::match(['get', 'post'], 'manajemen-web/struktur-ao/{act}/{type}/{id?}', [StrukturAoController::class, 'crudSO']);

    //Produksi
    Route::prefix('produksi')->group(function () {
        Route::get('/order-cetak-buku', [ProduksiController::class, 'index'])->name('produksi.view');
        Route::get('/order-cetak-buku/detail/{id}', [ProduksiController::class, 'detailProduksi'])->name('produksi.detail');
        Route::match(['get', 'post'], '/membuat-order-cetak-buku', [ProduksiController::class,'createProduksi'])->name('produksi.create');
        Route::match(['get', 'post'], '/mengubah-order-cetak-buku/{id}', [ProduksiController::class,'updateProduksi'])->name('produksi.update');
        Route::post('/hapus-order-cetak-buku', [ProduksiController::class,'deleteProduksi'])->name('produksi.delete');

    });

});
