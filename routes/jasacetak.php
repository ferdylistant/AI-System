<?php
use Illuminate\Support\Facades\Route;
$path = 'App\Http\Controllers';
Route::prefix('jasa-cetak/order-buku')->group(function () use ($path) {
    //? BUKU
    Route::get('/', $path . '\JasaCetak\OrderBukuController@index');
    Route::match(['get', 'post'], '/create', $path . '\JasaCetak\OrderBukuController@createOrder');
    Route::match(['get', 'post'], '/edit', $path . '\JasaCetak\OrderBukuController@updateOrder');
    Route::match(['get', 'post'], '/detail', $path . '\JasaCetak\OrderBukuController@detailOrder');
    Route::match(['get', 'post'], '/otorisasi-kabag', $path . '\JasaCetak\OrderBukuController@otorisasiKabag');
    Route::match(['get', 'post'], '/ajax/{cat}', $path . '\JasaCetak\OrderBukuController@ajaxRequest');
    //? NON-BUKU
    Route::get('/order-nonbuku', $path . '\JasaCetak\OrderNonBukuController@index');
});
