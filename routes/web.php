<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ManWeb\SettingController;
use App\Http\Controllers\Penerbitan\DeskripsiCoverController;
use App\Http\Controllers\ManWeb\{StrukturAoController,UsersController};
use App\Http\Controllers\MasterData\{ImprintController,KelompokBukuController,FormatBukuController};
use App\Http\Controllers\Produksi\{ProduksiController, EbookController,ProsesProduksiController,ProsesEbookController};
use App\Http\Controllers\Penerbitan\{PenulisController, NaskahController, PenilaianNaskahController , DeskripsiFinalController, DeskripsiProdukController, EditingController, PracetakSetterController, PracetakDesainerController};

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


    // Manajemen User
    Route::prefix('manajemen-web')->group(function () {
        //Users
        Route::get('/users', [UsersController::class, 'index']);
        Route::match(['get', 'post'], '/user/ajax/{act}/{id?}', [UsersController::class, 'ajaxUser']);
        Route::get('/user/{id}', [UsersController::class, 'selectUser'])->name('user.view');
        Route::post('/user/update-access', [UsersController::class, 'updateAccess']);
        //Struktur AO
        Route::get('/struktur-ao', [StrukturAoController::class, 'index']);
        Route::match(['get', 'post'], '/struktur-ao/{act}/{type}/{id?}', [StrukturAoController::class, 'crudSO']);
    });
    //Setting
    Route::prefix('setting')->group(function () {
        Route::get('/', [SettingController::class, 'index'])->name('setting.view');
        Route::match(['get', 'post'], '/{act}/{type}/{id?}', [SettingController::class, 'crudSetting']);
    });
    //Master Data
    Route::prefix('master')->group(function () {
        //Imprint
        Route::get('/imprint', [ImprintController::class, 'index'])->name('imprint.view');
        Route::match(['get', 'post'], '/imprint/tambah-imprint', [ImprintController::class, 'createImprint'])->name('imprint.create');
        Route::match(['get', 'post'], '/imprint/ubah-imprint', [ImprintController::class, 'updateImprint'])->name('imprint.update');
        Route::get('/imprint/hapus',[ImprintController::class, 'deleteImprint'])->name('imprint.delete');
        Route::post('/imprint/lihat-history', [ImprintController::class, 'lihatHistory'])->name('imprint.history');
        //Platform Digital
        Route::get('/platform-digital', [ImprintController::class, 'indexPlatform'])->name('platform.view');
        Route::match(['get', 'post'], '/platform-digital/tambah', [ImprintController::class, 'createPlatform'])->name('platform.create');
        Route::match(['get', 'post'], '/platform-digital/ubah', [ImprintController::class, 'updatePlatform'])->name('platform.update');
        Route::post('/platform-digital/hapus', [ImprintController::class, 'deletePlatform'])->name('platform.delete');
        Route::post('/platform-digital/lihat-history', [ImprintController::class, 'lihatHistoryPlatform'])->name('platform.history');
        //Kelompok Buku
        Route::get('/kelompok-buku',[KelompokBukuController::class,'index'])->name('kb.view');
        Route::match(['get', 'post'], '/kelompok-buku/tambah', [KelompokBukuController::class, 'createKbuku'])->name('kb.create');
        Route::match(['get', 'post'], '/kelompok-buku/ubah', [KelompokBukuController::class, 'updateKbuku'])->name('kb.update');
        Route::get('/kelompok-buku/hapus',[KelompokBukuController::class, 'deleteKbuku'])->name('kb.delete');
        Route::post('/kelompok-buku/lihat-history', [KelompokBukuController::class, 'lihatHistory'])->name('kb.history');
        //Format Buku
        Route::get('/format-buku',[FormatBukuController::class,'index'])->name('fb.view');
        Route::match(['get', 'post'], '/format-buku/tambah', [FormatBukuController::class, 'createFbuku'])->name('fb.create');
        Route::match(['get', 'post'], '/format-buku/ubah', [FormatBukuController::class, 'updateFbuku'])->name('fb.update');
        Route::get('/format-buku/hapus',[FormatBukuController::class, 'deleteFbuku'])->name('fb.delete');
        Route::post('/format-buku/lihat-history', [FormatBukuController::class, 'lihatHistory'])->name('fb.history');
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
        Route::get('/naskah', [NaskahController::class, 'index'])->name('naskah.view');
        Route::get('/naskah/ajax-call-page/{page}', [NaskahController::class, 'ajaxCallPage']);
        Route::match(['get', 'post'], '/naskah/melihat-naskah/{id}', [NaskahController::class, 'viewNaskah']);
        Route::match(['get', 'post'], '/naskah/membuat-naskah', [NaskahController::class, 'createNaskah']);
        Route::match(['get', 'post'], '/naskah/mengubah-naskah/{id}', [NaskahController::class, 'updateNaskah']);
        Route::post('/naskah/penilaian/{cat}', [PenilaianNaskahController::class, 'index']);
        Route::post('/naskah/timeline/{cat}', [TimelineController::class, 'index']);
        Route::post('/naskah/tandai-data-lengkap', [NaskahController::class, 'tandaDataLengkap']);
        Route::post('/naskah/lihat-history', [NaskahController::class, 'lihatHistoryNaskah'])->name('naskah.history');
        //Deskripsi Produk
        Route::get('/deskripsi/produk',[DeskripsiProdukController::class, 'index'])->name('despro.view');
        Route::get('/deskripsi/produk/detail',[DeskripsiProdukController::class, 'detailDeskripsiProduk'])->name('despro.detail');
        Route::match(['get', 'post'],'/deskripsi/produk/edit',[DeskripsiProdukController::class, 'editDeskripsiProduk'])->name('despro.edit');
        Route::post('/deskripsi/produk/update-status-progress',[DeskripsiProdukController::class, 'updateStatusProgress']);
        Route::post('/deskripsi/produk/lihat-history', [DeskripsiProdukController::class, 'lihatHistoryDespro'])->name('despro.history');
        Route::post('/deskripsi/produk/revisi',[DeskripsiProdukController::class, 'revisiDespro']);
        Route::post('/deskripsi/produk/pilih-judul',[DeskripsiProdukController::class, 'pilihJudul'])->name('despro.pilihjudul');
        Route::post('/deskripsi/produk/approve',[DeskripsiProdukController::class, 'approveDespro'])->name('despro.approve');
        //Deskripsi Final
        Route::get('/deskripsi/final',[DeskripsiFinalController::class,'index'])->name('desfin.view');
        Route::get('/deskripsi/final/detail',[DeskripsiFinalController::class, 'detailDeskripsiFinal'])->name('desfin.detail');
        Route::match(['get', 'post'],'/deskripsi/final/edit',[DeskripsiFinalController::class, 'editDeskripsiFinal'])->name('desfin.edit');
        Route::post('/deskripsi/final/update-status-progress',[DeskripsiFinalController::class, 'updateStatusProgress']);
        Route::post('/deskripsi/final/lihat-history', [DeskripsiFinalController::class, 'lihatHistoryDesfin'])->name('desfin.history');
        //Deskripsi Cover
        Route::get('/deskripsi/cover',[DeskripsiCoverController::class,'index'])->name('descov.view');
        Route::get('/deskripsi/cover/detail',[DeskripsiCoverController::class, 'detailDeskripsiCover'])->name('descov.detail');
        Route::match(['get', 'post'],'/deskripsi/cover/edit',[DeskripsiCoverController::class, 'editDeskripsiCover'])->name('descov.edit');
        Route::post('/deskripsi/cover/update-status-progress',[DeskripsiCoverController::class, 'updateStatusProgress']);
        Route::post('/deskripsi/cover/lihat-history', [DeskripsiCoverController::class, 'lihatHistoryDescov'])->name('descov.history');
        //Editing
        Route::get('/editing', [EditingController::class, 'index'])->name('editing.view');
        Route::get('/editing/detail', [EditingController::class, 'detailEditing'])->name('editing.detail');
        Route::match(['get', 'post'],'/editing/edit',[EditingController::class, 'editEditing'])->name('editing.edit');
        Route::post('/editing/update-status-progress',[EditingController::class, 'updateStatusProgress']);
        Route::post('/editing/lihat-history', [EditingController::class, 'lihatHistoryEditing'])->name('editing.history');
        Route::post('/editing/proses-kerja', [EditingController::class, 'prosesKerjaEditing'])->name('editing.proses');
        Route::post('/editing/selesai/{cat}/{id}', [EditingController::class, 'prosesSelesaiEditing'])->name('editing.selesai');
        //Pracetak Setter
        Route::get('/pracetak/setter', [PracetakSetterController::class, 'index'])->name('setter.view');
        Route::get('/pracetak/setter/detail', [PracetakSetterController::class, 'detailSetter'])->name('setter.detail');
        Route::match(['get', 'post'],'/pracetak/setter/edit',[PracetakSetterController::class, 'editSetter'])->name('setter.edit');
        Route::post('/pracetak/setter/update-status-progress',[PracetakSetterController::class, 'updateStatusProgress']);
        Route::post('/pracetak/setter/proses-kerja', [PracetakSetterController::class, 'prosesKerjaSetter'])->name('setter.proses');
        Route::post('/pracetak/setter/selesai/{cat}/{id}', [PracetakSetterController::class, 'prosesSelesaiSetter'])->name('setter.selesai');
        Route::post('/pracetak/setter/{act}',[PracetakSetterController::class, 'actionAjax']);
        //Pracetak Desainer
        Route::get('/pracetak/designer', [PracetakDesainerController::class, 'index'])->name('prades.view');
        Route::get('/pracetak/designer/detail', [PracetakDesainerController::class, 'detailPracetakDesainer'])->name('prades.detail');
        Route::match(['get', 'post'],'/pracetak/designer/edit',[PracetakDesainerController::class, 'editPracetakDesainer'])->name('prades.edit');
        Route::post('/pracetak/designer/update-status-progress',[PracetakDesainerController::class, 'updateStatusProgress']);
        Route::post('/pracetak/designer/lihat-history', [PracetakDesainerController::class, 'lihatHistoryPracetakDesainer'])->name('prades.history');
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
