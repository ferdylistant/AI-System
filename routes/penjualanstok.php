<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PenjualanStok\PenerimaanRekondisiController;
$path = 'App\Http\Controllers';
Route::prefix('penjualan-stok')->group(function () use ($path) {
    //Rekondisi
    Route::resource('/gudang/penerimaan-rekondisi',PenerimaanRekondisiController::class);
    //Penerimaan Buku Stok Andi
    Route::match(['get','post'],'/gudang/penerimaan-buku/andi', $path.'\PenjualanStok\PenerimaanBukuController@index');
    //Stok Andi
    Route::match(['get','post'],'/gudang/stok-buku/andi', $path.'\PenjualanStok\StokAndiController@index');
    Route::get('/gudang/stok-buku/andi/export-all', $path.'\PenjualanStok\StokAndiController@exportAll');
    Route::get('/gudang/stok-buku/andi/{id}', $path.'\PenjualanStok\StokAndiController@detail');
    Route::get('/gudang/stok-buku/andi/{id}/export', $path.'\PenjualanStok\StokAndiController@export');
    //Stok Non-Andi (Buku)
    Route::get('/gudang/non-andi/buku', $path.'\PenjualanStok\StokNonAndiController@index');
    //Stok Non-Andi (Non-Buku)
    Route::get('/gudang/non-andi/non-buku', $path.'\PenjualanStok\StokNonAndiController@index');
    // Route::get('/proses/cetak/detail', $path.'\Produksi\GudangController@detailProduksi')->name('proses.cetak.detail');
    // Route::match(['get', 'post'], '/proses/cetak/edit', $path.'\Produksi\GudangController@updateProduksi')->name('proses.cetak.update');
    // Route::match(['get', 'post'], '/proses/cetak/ajax/{cat}', $path.'\Produksi\GudangController@ajaxCall');
});
