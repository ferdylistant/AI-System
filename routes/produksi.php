<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Produksi\RekondisiController;
$path = "App\Http\Controllers";
Route::prefix('produksi')->group(function () use ($path) {
    //Proses Produksi Cetak
    Route::get('/proses/cetak', $path.'\Produksi\ProsesProduksiController@index');
    Route::get('/proses/cetak/detail', $path.'\Produksi\ProsesProduksiController@detailProduksi')->name('proses.cetak.detail');
    Route::match(['get', 'post'], '/proses/cetak/edit', $path.'\Produksi\ProsesProduksiController@updateProduksi')->name('proses.cetak.update');
    Route::match(['get', 'post'], '/proses/cetak/ajax/{cat}', $path.'\Produksi\ProsesProduksiController@ajaxCall');
    //Rekondisi
    Route::resource('/rekondisi',RekondisiController::class);
    //Proses Produksi E-book Multimedia
    Route::get('/proses/ebook-multimedia', $path.'\Produksi\ProsesEbookController@index')->name('proses.ebook.view');
    Route::get('/proses/ebook-multimedia/detail', $path.'\Produksi\ProsesEbookController@detailProduksi')->name('proses.ebook.detail');
    Route::match(['get', 'post'], '/proses/ebook-multimedia/edit', $path.'\Produksi\ProsesEbookController@updateProduksi')->name('proses.ebook.update');
});
