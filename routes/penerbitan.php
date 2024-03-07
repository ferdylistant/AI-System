<?php
use Illuminate\Support\Facades\Route;
$path = "App\Http\Controllers";
Route::prefix('penerbitan')->group(function () use ($path) {
    //Penulis
    Route::get('/penulis/export-all', $path.'\Penerbitan\PenulisController@exportAll');
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
    Route::match(['get','post'],'/naskah/ajax/{cat}', $path.'\Penerbitan\NaskahController@ajaxCallModal');
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
