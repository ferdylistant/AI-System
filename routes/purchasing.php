<?php

use Illuminate\Support\Facades\Route;

$path = "App\Http\Controllers";
Route::prefix('/purchasing')->group(function () use ($path) {
    // Stok Gudang Material
    Route::get('/stok-gudang-material', $path.'\Purchasing\StokGudangMaterialController@index')->name('sgm.view');
    Route::match(['get', 'post'], '/stok-gudang-material/tambah', $path.'\Purchasing\StokGudangMaterialController@createPermintaan')->name('sgm.create');
    Route::match(['get','post'], '/stok-gudang-material/ajax/{cat}', $path.'\Purchasing\StokGudangMaterialController@callAjax');
});
