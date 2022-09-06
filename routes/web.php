<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManWeb\UsersController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ManWeb\StrukturAoController;
use App\Http\Controllers\Produksi\{ProduksiController, EbookController,ProsesProduksiController,ProsesEbookController};
use App\Http\Controllers\Penerbitan\{PenulisController, NaskahController, PenilaianNaskahController, ImprintController, TimelineController};

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
Route::middleware(['auth'])->group(function() {
    //API
    Route::get('/get-layout', [ApiController::class, 'getPosisiLayout']);
    Route::get('/list-dami', [ApiController::class, 'listDami']);
    Route::get('/list-format-buku', [ApiController::class, 'listFormatBuku']);
    Route::get('/list-status-buku', [ApiController::class, 'listStatusBuku']);
    Route::get('/list-status-buku-ebook', [ApiController::class, 'listStatusBukuEbook']);
    Route::get('/list-pilihan-terbit', [ApiController::class, 'listPilihanTerbit']);
    Route::get('/list-status-cetak', [ApiController::class, 'listStatusCetak']);
    Route::get('/list-jenis-mesin', [ApiController::class, 'listJenisMesin']);
    Route::post('/update-tanggal-upload-ebook', [ApiController::class, 'updateTanggalUploadEbook']);
    Route::post('/update-tanggal-jadi-cetak', [ApiController::class, 'updateTanggalJadiCetak']);
    Route::post('/update-jumlah-cetak', [ApiController::class, 'updateJumlahCetak']);
    Route::get('/batal/pending/order-cetak/{id}', [ProduksiController::class, 'batalPendingOrderCetak']);
    Route::get('/batal/pending/order-ebook/{id}', [EbookController::class, 'batalPendingOrderEbook']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('notification', [NotificationController::class, 'index']);
    Route::get('/', [HomeController::class, 'index']);
    Route::post('import-db', [HomeController::class, 'importDB']);

    /* Home */
    Route::post('home/test', [HomeController::class, 'ajaxTest']);
    Route::post('home/penerbitan/{cat}', [HomeController::class, 'ajaxPenerbitan']);
    Route::post('public/penerbitan/fullcalendar/{cat}', [NaskahController::class, 'ajaxFromCalendar']);

    Route::get('manajemen-web/users', [UsersController::class, 'index']);
    Route::match(['get', 'post'], 'manajemen-web/user/ajax/{act}/{id?}', [UsersController::class, 'ajaxUser']);
    Route::get('manajemen-web/user/{id}', [UsersController::class, 'selectUser']);
    Route::post('manajemen-web/user/update-access', [UsersController::class, 'updateAccess']);

    /* Menu Struktur Organisasi [cabang, divisi, jabatan] */
    Route::get('manajemen-web/struktur-ao', [StrukturAoController::class, 'index']);
    Route::match(['get', 'post'], 'manajemen-web/struktur-ao/{act}/{type}/{id?}', [StrukturAoController::class, 'crudSO']);

    Route::prefix('master')->group(function () {
        //Imprint
        Route::get('/imprint', [ImprintController::class, 'index'])->name('imprint.view');
        Route::match(['get', 'post'], '/imprint/tambah-imprint', [ImprintController::class, 'createImprint'])->name('imprint.create');
        Route::match(['get', 'post'], '/imprint/ubah-imprint', [ImprintController::class, 'updateImprint'])->name('imprint.update');
        //Platform Digital
        Route::get('/platform-digital', [ImprintController::class, 'indexPlatform'])->name('platform.view');
        Route::match(['get', 'post'], '/platform-digital/tambah', [ImprintController::class, 'createPlatform'])->name('platform.create');
        Route::match(['get', 'post'], '/platform-digital/ubah', [ImprintController::class, 'updatePlatform'])->name('platform.update');
    });
    //Penerbitan
    Route::prefix('penerbitan')->group(function () {
        //Penulis
        Route::get('/penulis', [PenulisController::class, 'index']);
        Route::match(['get', 'post'], '/penulis/membuat-penulis', [PenulisController::class, 'createPenulis']);
        Route::match(['get', 'post'], '/penulis/mengubah-penulis/{id}', [PenulisController::class, 'updatePenulis']);
        Route::match(['get', 'post'], '/penulis/detail-penulis/{id}', [PenulisController::class, 'detailPenulis']);
        Route::post('/penulis/hapus-penulis', [PenulisController::class, 'deletePenulis']);
        //Naskah
        Route::get('/naskah', [NaskahController::class, 'index']);
        Route::get('/naskah/ajax-call-page/{page}', [NaskahController::class, 'ajaxCallPage']);
        Route::match(['get', 'post'], '/naskah/melihat-naskah/{id}', [NaskahController::class, 'viewNaskah']);
        Route::match(['get', 'post'], '/naskah/membuat-naskah', [NaskahController::class, 'createNaskah']);
        Route::match(['get', 'post'], '/naskah/mengubah-naskah/{id}', [NaskahController::class, 'updateNaskah']);
        Route::post('/naskah/penilaian/{cat}', [PenilaianNaskahController::class, 'index']);
        Route::post('/naskah/timeline/{cat}', [TimelineController::class, 'index']);
        //Order Cetak
        Route::get('/order-cetak', [ProduksiController::class, 'index'])->name('cetak.view');
        Route::get('/order-cetak/detail', [ProduksiController::class, 'detailProduksi'])->name('cetak.detail');
        Route::match(['get', 'post'], '/order-cetak/create', [ProduksiController::class,'createProduksi'])->name('cetak.create');
        Route::match(['get', 'post'], '/order-cetak/edit', [ProduksiController::class,'updateProduksi'])->name('cetak.update');
        Route::post('/hapus-order-cetak-buku', [ProduksiController::class,'deleteProduksi'])->name('cetak.delete');
        Route::post('/order-cetak/ajax/{cat}', [ProduksiController::class, 'ajaxRequest']);
        //Order Ebook
        Route::get('/order-ebook', [EbookController::class, 'index'])->name('ebook.view');
        Route::get('/order-ebook/detail', [EbookController::class, 'detailProduksi'])->name('ebook.detail');
        Route::match(['get', 'post'], '/order-ebook/create', [EbookController::class,'createProduksi'])->name('ebook.create');
        Route::match(['get', 'post'], '/order-ebook/edit', [EbookController::class,'updateProduksi'])->name('ebook.update');
        Route::post('/hapus-order-ebook-buku', [EbookController::class,'deleteProduksi'])->name('ebook.delete');
        Route::post('/order-ebook/ajax/{cat}', [EbookController::class, 'ajaxRequest']);
    });
    //Produksi
    Route::prefix('produksi')->group(function () {
        //Proses Produksi Cetak
        Route::get('/proses/cetak', [ProsesProduksiController::class, 'index'])->name('proses.cetak.view');
        Route::get('/proses/cetak/detail', [ProsesProduksiController::class, 'detailProduksi'])->name('proses.cetak.detail');
        Route::match(['get', 'post'], '/proses/cetak/edit', [ProsesProduksiController::class,'updateProduksi'])->name('proses.cetak.update');

        //Proses Produksi E-book Multimedia
        Route::get('/proses/ebook-multimedia', [ProsesEbookController::class,'index'])->name('proses.ebook.view');
        Route::get('/proses/ebook-multimedia/detail', [ProsesEbookController::class,'detailProduksi'])->name('proses.ebook.detail');
        Route::match(['get', 'post'], '/proses/ebook-multimedia/edit', [ProsesEbookController::class,'updateProduksi'])->name('proses.ebook.update');
    });

});
