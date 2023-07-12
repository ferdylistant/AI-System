<?php

use Illuminate\Http\Request;
use App\Events\TesWebsocketEvent;
use Illuminate\Support\Facades\Route;
use App\Models\Naskah;

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
$path = "App\Http\Controllers";
Route::get('/check-authentication', $path.'\ApiController@checkAuth');
Route::get('login', $path.'\AuthController@login')->name('login');
Route::post('do-login', $path.'\AuthController@doLogin');
Route::post('/forgot-password', $path.'\AuthController@submitForgetPasswordForm')->name('password.email');
Route::get('/reset-password/{token}', function ($token, Request $request) {
    return view('reset_password', ['token' => $token, 'email' => $request->email, 'title' => 'Reset Password']);
})->name('password.reset');
Route::post('reset-password', $path.'\AuthController@submitResetPasswordForm')->name('reset.password.post');
Route::get('tes-socket', function () {
    $data = 'Data coba-coba';
    event(new TesWebsocketEvent($data));
});
Route::middleware(['auth'])->group(function () use ($path) {
    //API
    Route::post('/update-status-activity',$path.'\ApiController@updateStatusActivity');
    Route::get('/get-layout', $path.'\ApiController@getPosisiLayout');
    Route::get('/list/{list}', $path.'\ApiController@requestAjax');
    Route::post('/update-tanggal-upload-ebook',$path.'\ApiController@updateTanggalUploadEbook');
    Route::post('/update-tanggal-jadi-cetak', $path.'\ApiController@updateTanggalJadiCetak');
    Route::post('/update-jumlah-cetak', $path.'\ApiController@updateJumlahCetak');
    Route::get('/batal/pending/order-cetak/{id}', $path.'\Produksi\ProduksiController@batalPendingOrderCetak');
    Route::get('/batal/pending/order-ebook/{id}', $path.'\Produksi\EbookController@batalPendingOrderEbook');
    Route::post('/logout', $path.'\AuthController@logout');
    Route::get('/', $path.'\HomeController@index');
    Route::post('import-db', $path.'\HomeController@importDB');

    /* Home */
    Route::match(['get', 'post'],'/api/home/{cat}', $path.'\HomeController@apiAjax');
    Route::post('home/test', $path.'\HomeController@ajaxTest');
    Route::post('home/penerbitan/{cat}', $path.'\HomeController@ajaxPenerbitan');
    Route::post('public/penerbitan/fullcalendar/{cat}', $path.'\Penerbitan\NaskahController@ajaxFromCalendar');

    Route::prefix('timeline')->group(function () use ($path) {
        Route::post('/{cat}', $path.'\Penerbitan\TimelineController@index');
    });

    // Notification
    Route::prefix('notification')->group(function () use ($path) {
        Route::post('/', $path.'\NotificationController@index');
        Route::get('/view-all', $path.'\NotificationController@viewAll')->name('notification.view_all');
    });

    // Manajemen User
    Route::prefix('manajemen-web')->group(function () use ($path) {
        //Users
        Route::get('/users', $path.'\ManWeb\UsersController@index');
        Route::get('/users/user-telah-dihapus', $path.'\ManWeb\UsersController@userTelahDihapus')->name('user.telah_dihapus');
        Route::match(['get', 'post'], '/user/ajax/{act}/{id?}', $path.'\ManWeb\UsersController@ajaxUser');
        Route::get('/user/{id}', $path.'\ManWeb\UsersController@selectUser')->name('user.view');
        Route::post('/user/update-access', $path.'\ManWeb\UsersController@updateAccess');
        Route::post('/user/restore', $path.'\ManWeb\UsersController@restoreUser')->name('user.restore');
        Route::post('/user/lihat-history', $path.'\ManWeb\UsersController@lihatHistoryUser')->name('user.history');
        //Struktur AO
        Route::get('/struktur-ao', $path.'\ManWeb\StrukturAoController@index');
        Route::match(['get', 'post'], '/struktur-ao/{act}/{type}/{id?}', $path.'\ManWeb\StrukturAoController@crudSO');
    });
    //Setting
    Route::prefix('setting')->group(function () use ($path) {
        Route::get('/', $path.'\ManWeb\SettingController@index')->name('setting.view');
        Route::match(['get', 'post'], '/{act}/{type}/{id?}', $path.'\ManWeb\SettingController@crudSetting');
    });
    //Master Data
    Route::prefix('master')->group(function () use ($path) {
        //Platform Digital
        Route::get('/platform-digital', $path.'\MasterData\ImprintController@indexPlatform')->name('platform.view');
        Route::get('/platform-digital/platform-telah-dihapus', $path.'\MasterData\ImprintController@platformTelahDihapus')->name('platform.telah_dihapus');
        Route::match(['get', 'post'], '/platform-digital/tambah', $path.'\MasterData\ImprintController@createPlatform')->name('platform.create');
        Route::match(['get', 'post'], '/platform-digital/ubah', $path.'\MasterData\ImprintController@updatePlatform')->name('platform.update');
        Route::post('/platform-digital/hapus', $path.'\MasterData\ImprintController@deletePlatform')->name('platform.delete');
        Route::post('/platform-digital/restore', $path.'\MasterData\ImprintController@restorePlatform')->name('platform.restore');
        Route::post('/platform-digital/lihat-history', $path.'\MasterData\ImprintController@lihatHistoryPlatform')->name('platform.history');
        //Imprint
        Route::get('/imprint', $path.'\MasterData\ImprintController@index')->name('imprint.view');
        Route::get('/imprint/imprint-telah-dihapus', $path.'\MasterData\ImprintController@imprintTelahDihapus')->name('imprint.telah_dihapus');
        Route::match(['get', 'post'], '/imprint/tambah', $path.'\MasterData\ImprintController@createImprint')->name('imprint.create');
        Route::match(['get', 'post'], '/imprint/ubah', $path.'\MasterData\ImprintController@updateImprint')->name('imprint.update');
        Route::post('/imprint/hapus', $path.'\MasterData\ImprintController@deleteImprint')->name('imprint.delete');
        Route::post('/imprint/restore', $path.'\MasterData\ImprintController@restoreImprint')->name('imprint.restore');
        Route::post('/imprint/lihat-history', $path.'\MasterData\ImprintController@lihatHistoryImprint')->name('imprint.history');
        //Kelompok Buku
        Route::get('/kelompok-buku', $path.'\MasterData\KelompokBukuController@index')->name('kb.view');
        Route::get('/kelompok-buku/kelompok-buku-telah-dihapus', $path.'\MasterData\KelompokBukuController@kBukuTelahDihapus')->name('kb.telah_dihapus');
        Route::match(['get', 'post'], '/kelompok-buku/tambah', $path.'\MasterData\KelompokBukuController@createKbuku')->name('kb.create');
        Route::match(['get', 'post'], '/kelompok-buku/ubah', $path.'\MasterData\KelompokBukuController@updateKbuku')->name('kb.update');
        Route::post('/kelompok-buku/hapus', $path.'\MasterData\KelompokBukuController@deleteKbuku')->name('kb.delete');
        Route::post('/kelompok-buku/restore', $path.'\MasterData\KelompokBukuController@restoreKBuku')->name('kb.restore');
        Route::post('/kelompok-buku/lihat-history', $path.'\MasterData\KelompokBukuController@lihatHistoryKBuku')->name('kb.history');
        //Format Buku
        Route::get('/format-buku', $path.'\MasterData\FormatBukuController@index')->name('fb.view');
        Route::get('/format-buku/format-buku-telah-dihapus', $path.'\MasterData\FormatBukuController@fBukuTelahDihapus')->name('fb.telah_dihapus');
        Route::match(['get', 'post'], '/format-buku/tambah', $path.'\MasterData\FormatBukuController@createFbuku')->name('fb.create');
        Route::match(['get', 'post'], '/format-buku/ubah', $path.'\MasterData\FormatBukuController@updateFbuku')->name('fb.update');
        Route::post('/format-buku/hapus', $path.'\MasterData\FormatBukuController@deleteFbuku')->name('fb.delete');
        Route::post('/format-buku/restore', $path.'\MasterData\FormatBukuController@restoreFBuku')->name('fb.restore');
        Route::post('/format-buku/lihat-history', $path.'\MasterData\FormatBukuController@lihatHistoryFBuku')->name('fb.history');
        //JENIS MESIN
        Route::get('/jenis-mesin', $path.'\MasterData\JenisMesinController@index')->name('jm.view');
        Route::get('/jenis-mesin/jenis-mesin-telah-dihapus', $path.'\MasterData\JenisMesinController@jMesinTelahDihapus')->name('jm.telah_dihapus');
        Route::match(['get', 'post'], '/jenis-mesin/tambah', $path.'\MasterData\JenisMesinController@createJmesin')->name('jm.create');
        Route::match(['get', 'post'], '/jenis-mesin/ubah', $path.'\MasterData\JenisMesinController@updateJmesin')->name('jm.update');
        Route::post('/jenis-mesin/hapus', $path.'\MasterData\JenisMesinController@deleteJmesin')->name('jm.delete');
        Route::post('/jenis-mesin/restore', $path.'\MasterData\JenisMesinController@restoreJmesin')->name('jm.restore');
        Route::post('/jenis-mesin/lihat-history', $path.'\MasterData\JenisMesinController@lihatHistoryJmesin')->name('jm.history');

    });
    //Penerbitan
    Route::prefix('penerbitan')->group(function () use ($path) {
        //Penulis
        Route::get('/penulis/export/{format}', $path.'\Penerbitan\PenulisController@exportData');
        Route::get('/penulis', $path.'\Penerbitan\PenulisController@index');
        Route::get('/penulis/penulis-telah-dihapus', $path.'\Penerbitan\PenulisController@penulisTelahDihapus')->name('penulis.telah_dihapus');
        Route::match(['get', 'post'], '/penulis/membuat-penulis', $path.'\Penerbitan\PenulisController@createPenulis');
        Route::match(['get', 'post'], '/penulis/mengubah-penulis/{id}', $path.'\Penerbitan\PenulisController@updatePenulis');
        Route::match(['get', 'post'], '/penulis/detail-penulis/{id}', $path.'\Penerbitan\PenulisController@detailPenulis');
        Route::post('/penulis/hapus-penulis', $path.'\Penerbitan\PenulisController@deletePenulis');
        Route::post('/penulis/restore', $path.'\Penerbitan\PenulisController@restorePenulis')->name('penulis.restore');
        Route::post('/penulis/lihat-history', $path.'\Penerbitan\PenulisController@lihatHistoryPenulis')->name('penulis.history');

        //Naskah
        Route::get('/naskah', $path.'\Penerbitan\NaskahController@index')->name('naskah.view');
        Route::match(['get','post'],'/naskah/restore', $path.'\Penerbitan\NaskahController@restorePage')->name('naskah.restore');
        Route::get('/naskah/ajax-call-page/{page}', $path.'\Penerbitan\NaskahController@ajaxCallPage');
        Route::match(['get', 'post'], '/naskah/melihat-naskah/{id}', $path.'\Penerbitan\NaskahController@viewNaskah');
        Route::match(['get', 'post'], '/naskah/membuat-naskah', $path.'\Penerbitan\NaskahController@createNaskah');
        Route::match(['get', 'post'], '/naskah/mengubah-naskah/{id}', $path.'\Penerbitan\NaskahController@updateNaskah');
        Route::post('/naskah/penilaian/{cat}', $path.'\Penerbitan\PenilaianNaskahController@index');
        Route::post('/naskah/tandai-data-lengkap', $path.'\Penerbitan\NaskahController@tandaDataLengkap');
        Route::post('/naskah/ajax/{cat}', $path.'\Penerbitan\NaskahController@ajaxCallModal');
        //Deskripsi Produk
        Route::get('/deskripsi/produk', $path.'\Penerbitan\DeskripsiProdukController@index')->name('despro.view');
        Route::get('/deskripsi/produk/detail', $path.'\Penerbitan\DeskripsiProdukController@detailDeskripsiProduk')->name('despro.detail');
        Route::match(['get', 'post'], '/deskripsi/produk/edit', $path.'\Penerbitan\DeskripsiProdukController@editDeskripsiProduk')->name('despro.edit');
        Route::post('/deskripsi/produk/pilih-judul', $path.'\Penerbitan\DeskripsiProdukController@pilihJudul')->name('despro.pilihjudul');
        Route::post('/deskripsi/produk/ajax/{cat}', $path.'\Penerbitan\DeskripsiProdukController@requestAjax');
        //Deskripsi Final
        Route::get('/deskripsi/final', $path.'\Penerbitan\DeskripsiFinalController@index')->name('desfin.view');
        Route::get('/deskripsi/final/detail', $path.'\Penerbitan\DeskripsiFinalController@detailDeskripsiFinal')->name('desfin.detail');
        Route::match(['get', 'post'], '/deskripsi/final/edit', $path.'\Penerbitan\DeskripsiFinalController@editDeskripsiFinal')->name('desfin.edit');
        Route::post('/deskripsi/final/ajax/{cat}', $path.'\Penerbitan\DeskripsiFinalController@ajaxCall');
        //Deskripsi Cover
        Route::get('/deskripsi/cover', $path.'\Penerbitan\DeskripsiCoverController@index')->name('descov.view');
        Route::get('/deskripsi/cover/detail', $path.'\Penerbitan\DeskripsiCoverController@detailDeskripsiCover')->name('descov.detail');
        Route::match(['get', 'post'], '/deskripsi/cover/edit', $path.'\Penerbitan\DeskripsiCoverController@editDeskripsiCover')->name('descov.edit');
        Route::post('/deskripsi/cover/ajax/{cat}', $path.'\Penerbitan\DeskripsiCoverController@ajaxCall');
        //Deskripsi Turun Cetak
        Route::get('/deskripsi/turun-cetak', $path.'\Penerbitan\DeskripsiTurunCetakController@index')->name('desturcet.view');
        Route::match(['get', 'post'], '/deskripsi/turun-cetak/detail', $path.'\Penerbitan\DeskripsiTurunCetakController@detailDeskripsiTurunCetak')->name('desturcet.detail');
        Route::match(['get', 'post'], '/deskripsi/turun-cetak/edit', $path.'\Penerbitan\DeskripsiTurunCetakController@editDeskripsiTurunCetak')->name('desturcet.edit');
        Route::post('/deskripsi/turun-cetak/ajax/{act}', $path.'\Penerbitan\DeskripsiTurunCetakController@actionAjax');
        //Editing
        Route::get('/editing', $path.'\Penerbitan\EditingController@index')->name('editing.view');
        Route::match(['get', 'post'],'/editing/detail', $path.'\Penerbitan\EditingController@detailEditing')->name('editing.detail');
        Route::match(['get', 'post'], '/editing/edit', $path.'\Penerbitan\EditingController@editEditing')->name('editing.edit');
        Route::post('/editing/ajax/{cat}', $path.'\Penerbitan\EditingController@ajaxCall');
        Route::post('/editing/selesai/{cat}/{id}', $path.'\Penerbitan\EditingController@prosesSelesaiEditing')->name('editing.selesai');
        //Pracetak Setter
        Route::get('/pracetak/setter', $path.'\Penerbitan\PracetakSetterController@index')->name('setter.view');
        Route::get('/pracetak/setter/detail', $path.'\Penerbitan\PracetakSetterController@detailSetter')->name('setter.detail');
        Route::match(['get', 'post'], '/pracetak/setter/edit', $path.'\Penerbitan\PracetakSetterController@editSetter')->name('setter.edit');
        Route::post('/pracetak/setter/selesai/{cat}/{id}', $path.'\Penerbitan\PracetakSetterController@prosesSelesaiSetter')->name('setter.selesai');
        Route::post('/pracetak/setter/ajax/{act}', $path.'\Penerbitan\PracetakSetterController@actionAjax');
        //Pracetak Desainer
        Route::get('/pracetak/designer', $path.'\Penerbitan\PracetakDesainerController@index')->name('prades.view');
        Route::get('/pracetak/designer/detail', $path.'\Penerbitan\PracetakDesainerController@detailPracetakDesainer')->name('prades.detail');
        Route::match(['get', 'post'], '/pracetak/designer/edit', $path.'\Penerbitan\PracetakDesainerController@editDesigner')->name('prades.edit');
        Route::post('/pracetak/designer/selesai/{cat}/{id}', $path.'\Penerbitan\PracetakDesainerController@prosesSelesaiDesigner')->name('prades.selesai');
        Route::post('/pracetak/designer/ajax/{act}', $path.'\Penerbitan\PracetakDesainerController@actionAjax');
        //Order Cetak
        Route::get('/order-cetak', $path.'\Penerbitan\OrderCetakController@index')->name('cetak.view');
        Route::get('/order-cetak/detail', $path.'\Penerbitan\OrderCetakController@detailOrderCetak')->name('cetak.detail');
        Route::match(['get', 'post'], '/order-cetak/edit', $path.'\Penerbitan\OrderCetakController@updateOrderCetak')->name('cetak.update');
        Route::match(['get','post'],'/order-cetak/ajax/{cat}', $path.'\Penerbitan\OrderCetakController@ajaxRequest');
        Route::get('/order-cetak/print/{id}',$path.'\Penerbitan\OrderCetakController@printPdf');
        //Order Ebook
        Route::get('/order-ebook', $path.'\Penerbitan\OrderEbookController@index')->name('ebook.view');
        Route::get('/order-ebook/detail', $path.'\Penerbitan\OrderEbookController@detailOrderEbook')->name('ebook.detail');
        Route::match(['get', 'post'], '/order-ebook/edit', $path.'\Penerbitan\OrderEbookController@updateOrderEbook')->name('ebook.update');
        Route::post('/order-ebook/ajax/{cat}', $path.'\Penerbitan\OrderEbookController@ajaxRequest');
    });
    Route::prefix('jasa-cetak/order-buku')->group(function () use ($path) {
        //? BUKU
        Route::get('/',$path.'\JasaCetak\OrderBukuController@index');
        Route::match(['get', 'post'], '/create', $path.'\JasaCetak\OrderBukuController@createOrder');
        Route::match(['get', 'post'], '/edit', $path.'\JasaCetak\OrderBukuController@updateOrder');
        Route::match(['get', 'post'], '/detail', $path.'\JasaCetak\OrderBukuController@detailOrder');
        Route::match(['get', 'post'], '/otorisasi-kabag', $path.'\JasaCetak\OrderBukuController@otorisasiKabag');
        Route::match(['get', 'post'],'/ajax/{cat}', $path.'\JasaCetak\OrderBukuController@ajaxRequest');
        //? NON-BUKU
        Route::get('/order-nonbuku',$path.'\JasaCetak\OrderNonBukuController@index');
    });
    //Produksi
    Route::prefix('produksi')->group(function () use ($path) {
        //Proses Produksi Cetak
        Route::get('/proses/cetak', $path.'\Produksi\ProsesProduksiController@index');
        Route::get('/proses/cetak/detail', $path.'\Produksi\ProsesProduksiController@detailProduksi')->name('proses.cetak.detail');
        Route::match(['get', 'post'], '/proses/cetak/edit', $path.'\Produksi\ProsesProduksiController@updateProduksi')->name('proses.cetak.update');
        Route::match(['get', 'post'], '/proses/cetak/ajax/{cat}', $path.'\Produksi\ProsesProduksiController@ajaxCall');

        //Proses Produksi E-book Multimedia
        Route::get('/proses/ebook-multimedia', $path.'\Produksi\ProsesEbookController@index')->name('proses.ebook.view');
        Route::get('/proses/ebook-multimedia/detail', $path.'\Produksi\ProsesEbookController@detailProduksi')->name('proses.ebook.detail');
        Route::match(['get', 'post'], '/proses/ebook-multimedia/edit', $path.'\Produksi\ProsesEbookController@updateProduksi')->name('proses.ebook.update');
    });

    Route::get('search', function () {
        $query = ''; // <-- Change the query for testing.

        $articles = Naskah::search($query)->get();

        return $articles;
    });
});
// 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
