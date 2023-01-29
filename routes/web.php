<?php

use Illuminate\Http\Request;
use App\Events\TesWebsocketEvent;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManWeb\{StrukturAoController, UsersController, SettingController};
use App\Http\Controllers\{AuthController, ApiController, HomeController, NotificationController};
use App\Http\Controllers\Produksi\{EbookController, ProsesProduksiController, ProsesEbookController};
use App\Http\Controllers\MasterData\{ImprintController, KelompokBukuController, FormatBukuController};
use App\Http\Controllers\Penerbitan\{OrderCetakController, OrderEbookController, PenulisController, NaskahController, PenilaianNaskahController, DeskripsiCoverController, DeskripsiFinalController, DeskripsiProdukController, DeskripsiTurunCetakController, EditingController, PracetakSetterController, PracetakDesainerController};

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
Route::post('/forgot-password', [AuthController::class, 'submitForgetPasswordForm'])->name('password.email');
Route::get('/reset-password/{token}', function ($token,Request $request) {
    return view('reset_password', ['token' => $token,'email'=> $request->email,'title' => 'Reset Password']);
})->name('password.reset');
Route::post('reset-password', [AuthController::class, 'submitResetPasswordForm'])->name('reset.password.post');
Route::get('tes-socket', function () {
    $data = 'Data coba-coba';
    event(new TesWebsocketEvent($data));
});
Route::middleware(['auth'])->group(function () {
    //API
    Route::get('/get-layout', [ApiController::class, 'getPosisiLayout']);
    Route::get('/list/{list}', [ApiController::class, 'requestAjax']);
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
        //Platform Digital
        Route::get('/platform-digital', [ImprintController::class, 'indexPlatform'])->name('platform.view');
        Route::get('/platform-digital/platform-telah-dihapus', [ImprintController::class, 'platformTelahDihapus'])->name('platform.telah_dihapus');
        Route::match(['get', 'post'], '/platform-digital/tambah', [ImprintController::class, 'createPlatform'])->name('platform.create');
        Route::match(['get', 'post'], '/platform-digital/ubah', [ImprintController::class, 'updatePlatform'])->name('platform.update');
        Route::post('/platform-digital/hapus', [ImprintController::class, 'deletePlatform'])->name('platform.delete');
        Route::post('/platform-digital/restore', [ImprintController::class, 'restorePlatform'])->name('platform.restore');
        Route::post('/platform-digital/lihat-history', [ImprintController::class, 'lihatHistoryPlatform'])->name('platform.history');
        //Imprint
        Route::get('/imprint', [ImprintController::class, 'index'])->name('imprint.view');
        Route::get('/imprint/imprint-telah-dihapus', [ImprintController::class, 'imprintTelahDihapus'])->name('imprint.telah_dihapus');
        Route::match(['get', 'post'], '/imprint/tambah', [ImprintController::class, 'createImprint'])->name('imprint.create');
        Route::match(['get', 'post'], '/imprint/ubah', [ImprintController::class, 'updateImprint'])->name('imprint.update');
        Route::post('/imprint/hapus', [ImprintController::class, 'deleteImprint'])->name('imprint.delete');
        Route::post('/imprint/restore', [ImprintController::class, 'restoreImprint'])->name('imprint.restore');
        Route::post('/imprint/lihat-history', [ImprintController::class, 'lihatHistoryImprint'])->name('imprint.history');
        //Kelompok Buku
        Route::get('/kelompok-buku', [KelompokBukuController::class, 'index'])->name('kb.view');
        Route::get('/kelompok-buku/kelompok-buku-telah-dihapus', [KelompokBukuController::class, 'kBukuTelahDihapus'])->name('kb.telah_dihapus');
        Route::match(['get', 'post'], '/kelompok-buku/tambah', [KelompokBukuController::class, 'createKbuku'])->name('kb.create');
        Route::match(['get', 'post'], '/kelompok-buku/ubah', [KelompokBukuController::class, 'updateKbuku'])->name('kb.update');
        Route::post('/kelompok-buku/hapus', [KelompokBukuController::class, 'deleteKbuku'])->name('kb.delete');
        Route::post('/kelompok-buku/restore', [KelompokBukuController::class, 'restoreKBuku'])->name('kb.restore');
        Route::post('/kelompok-buku/lihat-history', [KelompokBukuController::class, 'lihatHistoryKBuku'])->name('kb.history');
        //Format Buku
        Route::get('/format-buku', [FormatBukuController::class, 'index'])->name('fb.view');
        Route::get('/format-buku/format-buku-telah-dihapus', [FormatBukuController::class, 'fBukuTelahDihapus'])->name('fb.telah_dihapus');
        Route::match(['get', 'post'], '/format-buku/tambah', [FormatBukuController::class, 'createFbuku'])->name('fb.create');
        Route::match(['get', 'post'], '/format-buku/ubah', [FormatBukuController::class, 'updateFbuku'])->name('fb.update');
        Route::post('/format-buku/hapus', [FormatBukuController::class, 'deleteFbuku'])->name('fb.delete');
        Route::post('/format-buku/restore', [FormatBukuController::class, 'restoreFBuku'])->name('fb.restore');
        Route::post('/format-buku/lihat-history', [FormatBukuController::class, 'lihatHistoryFBuku'])->name('fb.history');
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
        // Route::post('/naskah/timeline/{cat}', [TimelineController::class, 'index']);
        Route::post('/naskah/tandai-data-lengkap', [NaskahController::class, 'tandaDataLengkap']);
        Route::post('/naskah/lihat-history', [NaskahController::class, 'lihatHistoryNaskah'])->name('naskah.history');
        //Deskripsi Produk
        Route::get('/deskripsi/produk', [DeskripsiProdukController::class, 'index'])->name('despro.view');
        Route::get('/deskripsi/produk/detail', [DeskripsiProdukController::class, 'detailDeskripsiProduk'])->name('despro.detail');
        Route::match(['get', 'post'], '/deskripsi/produk/edit', [DeskripsiProdukController::class, 'editDeskripsiProduk'])->name('despro.edit');
        Route::post('/deskripsi/produk/pilih-judul', [DeskripsiProdukController::class, 'pilihJudul'])->name('despro.pilihjudul');
        Route::post('/deskripsi/produk/ajax/{cat}', [DeskripsiProdukController::class, 'requestAjax']);
        //Deskripsi Final
        Route::get('/deskripsi/final', [DeskripsiFinalController::class, 'index'])->name('desfin.view');
        Route::get('/deskripsi/final/detail', [DeskripsiFinalController::class, 'detailDeskripsiFinal'])->name('desfin.detail');
        Route::match(['get', 'post'], '/deskripsi/final/edit', [DeskripsiFinalController::class, 'editDeskripsiFinal'])->name('desfin.edit');
        Route::post('/deskripsi/final/update-status-progress', [DeskripsiFinalController::class, 'updateStatusProgress']);
        Route::post('/deskripsi/final/lihat-history', [DeskripsiFinalController::class, 'lihatHistoryDesfin'])->name('desfin.history');
        //Deskripsi Cover
        Route::get('/deskripsi/cover', [DeskripsiCoverController::class, 'index'])->name('descov.view');
        Route::get('/deskripsi/cover/detail', [DeskripsiCoverController::class, 'detailDeskripsiCover'])->name('descov.detail');
        Route::match(['get', 'post'], '/deskripsi/cover/edit', [DeskripsiCoverController::class, 'editDeskripsiCover'])->name('descov.edit');
        Route::post('/deskripsi/cover/update-status-progress', [DeskripsiCoverController::class, 'updateStatusProgress']);
        Route::post('/deskripsi/cover/lihat-history', [DeskripsiCoverController::class, 'lihatHistoryDescov'])->name('descov.history');
        //Deskripsi Turun Cetak
        Route::get('/deskripsi/turun-cetak', [DeskripsiTurunCetakController::class, 'index'])->name('desturcet.view');
        Route::match(['get', 'post'], '/deskripsi/turun-cetak/detail', [DeskripsiTurunCetakController::class, 'detailDeskripsiTurunCetak'])->name('desturcet.detail');
        Route::match(['get', 'post'], '/deskripsi/turun-cetak/edit', [DeskripsiTurunCetakController::class, 'editDeskripsiTurunCetak'])->name('desturcet.edit');
        Route::post('/deskripsi/turun-cetak/{act}', [DeskripsiTurunCetakController::class, 'actionAjax']);
        //Editing
        Route::get('/editing', [EditingController::class, 'index'])->name('editing.view');
        Route::get('/editing/detail', [EditingController::class, 'detailEditing'])->name('editing.detail');
        Route::match(['get', 'post'], '/editing/edit', [EditingController::class, 'editEditing'])->name('editing.edit');
        Route::post('/editing/update-status-progress', [EditingController::class, 'updateStatusProgress']);
        Route::post('/editing/lihat-history', [EditingController::class, 'lihatHistoryEditing'])->name('editing.history');
        Route::post('/editing/proses-kerja', [EditingController::class, 'prosesKerjaEditing'])->name('editing.proses');
        Route::post('/editing/selesai/{cat}/{id}', [EditingController::class, 'prosesSelesaiEditing'])->name('editing.selesai');
        //Pracetak Setter
        Route::get('/pracetak/setter', [PracetakSetterController::class, 'index'])->name('setter.view');
        Route::get('/pracetak/setter/detail', [PracetakSetterController::class, 'detailSetter'])->name('setter.detail');
        Route::match(['get', 'post'], '/pracetak/setter/edit', [PracetakSetterController::class, 'editSetter'])->name('setter.edit');
        Route::post('/pracetak/setter/selesai/{cat}/{id}', [PracetakSetterController::class, 'prosesSelesaiSetter'])->name('setter.selesai');
        Route::post('/pracetak/setter/{act}', [PracetakSetterController::class, 'actionAjax']);
        //Pracetak Desainer
        Route::get('/pracetak/designer', [PracetakDesainerController::class, 'index'])->name('prades.view');
        Route::get('/pracetak/designer/detail', [PracetakDesainerController::class, 'detailPracetakDesainer'])->name('prades.detail');
        Route::match(['get', 'post'], '/pracetak/designer/edit', [PracetakDesainerController::class, 'editDesigner'])->name('prades.edit');
        Route::post('/pracetak/designer/selesai/{cat}/{id}', [PracetakDesainerController::class, 'prosesSelesaiDesigner'])->name('prades.selesai')->middleware('throttle:5,1');
        Route::post('/pracetak/designer/{act}', [PracetakDesainerController::class, 'actionAjax'])->middleware('throttle:5,1');
        //Order Cetak
        Route::get('/order-cetak', [OrderCetakController::class, 'index'])->name('cetak.view');
        Route::get('/order-cetak/detail', [OrderCetakController::class, 'detailOrderCetak'])->name('cetak.detail');
        Route::match(['get', 'post'], '/order-cetak/edit', [OrderCetakController::class, 'updateOrderCetak'])->name('cetak.update');
        Route::post('/order-cetak/ajax/{cat}', [OrderCetakController::class, 'ajaxRequest']);
        //Order Ebook
        Route::get('/order-ebook', [OrderEbookController::class, 'index'])->name('ebook.view');
        Route::get('/order-ebook/detail', [OrderEbookController::class, 'detailOrderEbook'])->name('ebook.detail');
        Route::match(['get', 'post'], '/order-ebook/edit', [OrderEbookController::class, 'updateOrderEbook'])->name('ebook.update');
        Route::post('/order-ebook/ajax/{cat}', [OrderEbookController::class, 'ajaxRequest']);
    });
    //Produksi
    Route::prefix('produksi')->group(function () {
        //Proses Produksi Cetak
        Route::get('/proses/cetak', [ProsesProduksiController::class, 'index'])->name('proses.cetak.view');
        Route::get('/proses/cetak/detail', [ProsesProduksiController::class, 'detailProduksi'])->name('proses.cetak.detail');
        Route::match(['get', 'post'], '/proses/cetak/edit', [ProsesProduksiController::class, 'updateProduksi'])->name('proses.cetak.update');

        //Proses Produksi E-book Multimedia
        Route::get('/proses/ebook-multimedia', [ProsesEbookController::class, 'index'])->name('proses.ebook.view');
        Route::get('/proses/ebook-multimedia/detail', [ProsesEbookController::class, 'detailProduksi'])->name('proses.ebook.detail');
        Route::match(['get', 'post'], '/proses/ebook-multimedia/edit', [ProsesEbookController::class, 'updateProduksi'])->name('proses.ebook.update');
    });
});
