<?php

use Illuminate\Support\Facades\Route;

$path = "App\Http\Controllers";
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
    //SUB Kelompok Buku
    Route::get('/sub-kelompok-buku', $path.'\MasterData\SubKelompokBukuController@index')->name('skb.view');
    Route::get('/sub-kelompok-buku/sub-kelompok-buku-telah-dihapus', $path.'\MasterData\SubKelompokBukuController@skBukuTelahDihapus')->name('skb.telah_dihapus');
    Route::match(['get', 'post'], '/sub-kelompok-buku/tambah', $path.'\MasterData\SubKelompokBukuController@createSKbuku')->name('skb.create');
    Route::match(['get', 'post'], '/sub-kelompok-buku/ubah', $path.'\MasterData\SubKelompokBukuController@updateSKbuku')->name('skb.update');
    Route::post('/sub-kelompok-buku/hapus', $path.'\MasterData\SubKelompokBukuController@deleteSKbuku')->name('skb.delete');
    Route::post('/sub-kelompok-buku/restore', $path.'\MasterData\SubKelompokBukuController@restoreSKBuku')->name('skb.restore');
    Route::post('/sub-kelompok-buku/lihat-history', $path.'\MasterData\SubKelompokBukuController@lihatHistorySKBuku')->name('skb.history');
    Route::get('/sub-kelompok-buku/ajax-select', $path.'\MasterData\SubKelompokBukuController@ajaxSelect');
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
    //Operator MESIN
    Route::get('/operator-mesin', $path.'\MasterData\OperatorMesinController@index')->name('om.view');
    Route::get('/operator-mesin/operator-mesin-telah-dihapus', $path.'\MasterData\OperatorMesinController@oMesinTelahDihapus')->name('om.telah_dihapus');
    Route::match(['get', 'post'], '/operator-mesin/tambah', $path.'\MasterData\OperatorMesinController@createOmesin')->name('om.create');
    Route::match(['get', 'post'], '/operator-mesin/ubah', $path.'\MasterData\OperatorMesinController@updateOmesin')->name('om.update');
    Route::post('/operator-mesin/hapus', $path.'\MasterData\OperatorMesinController@deleteOmesin')->name('om.delete');
    Route::post('/operator-mesin/restore', $path.'\MasterData\OperatorMesinController@restoreOmesin')->name('om.restore');
    Route::post('/operator-mesin/lihat-history', $path.'\MasterData\OperatorMesinController@lihatHistoryOmesin')->name('om.history');
    //Rack List
    Route::get('/rack-list', $path.'\MasterData\RackController@index')->name('rl.view');
    Route::get('/rack-list/rack-list-telah-dihapus', $path.'\MasterData\RackController@rackListTelahDihapus')->name('rl.telah_dihapus');
    Route::match(['get', 'post'], '/rack-list/tambah', $path.'\MasterData\RackController@createRackList')->name('rl.create');
    Route::match(['get', 'post'], '/rack-list/ubah', $path.'\MasterData\RackController@updateRackList')->name('rl.update');
    Route::post('/rack-list/hapus', $path.'\MasterData\RackController@deleteRackList')->name('rl.delete');
    Route::post('/rack-list/restore', $path.'\MasterData\RackController@restoreRackList')->name('rl.restore');
    Route::post('/rack-list/lihat-history', $path.'\MasterData\RackController@lihatHistoryRackList')->name('rl.history');
    Route::match(['get','post'],'/rack-list/ajax/{cat}', $path.'\MasterData\RackController@callAjax');
    //Operator Gudang
    Route::get('/operator-gudang', $path.'\MasterData\OperatorGudangController@index')->name('og.view');
    Route::get('/operator-gudang/operator-gudang-telah-dihapus', $path.'\MasterData\OperatorGudangController@operatorGudangTelahDihapus')->name('og.telah_dihapus');
    Route::match(['get', 'post'], '/operator-gudang/tambah', $path.'\MasterData\OperatorGudangController@createOperatorGudang')->name('og.create');
    Route::match(['get', 'post'], '/operator-gudang/ubah', $path.'\MasterData\OperatorGudangController@updateOperatorGudang')->name('og.update');
    Route::post('/operator-gudang/hapus', $path.'\MasterData\OperatorGudangController@deleteOperatorGudang')->name('og.delete');
    Route::post('/operator-gudang/restore', $path.'\MasterData\OperatorGudangController@restoreOperatorGudang')->name('og.restore');
    Route::post('/operator-gudang/lihat-history', $path.'\MasterData\OperatorGudangController@lihatHistoryOperatorGudang')->name('og.history');
    Route::match(['get','post'],'/operator-gudang/ajax/{cat}', $path.'\MasterData\OperatorGudangController@callAjax');
    //Harga Jual
    Route::get('/harga-jual', $path.'\MasterData\HargaJualController@index')->name('hj.view');
    Route::get('/harga-jual/harga-jual-telah-dihapus', $path.'\MasterData\HargaJualController@hargaJualTelahDihapus')->name('hj.telah_dihapus');
    Route::match(['get', 'post'], '/harga-jual/tambah', $path.'\MasterData\HargaJualController@createHargaJual')->name('hj.create');
    Route::match(['get', 'post'], '/harga-jual/ubah', $path.'\MasterData\HargaJualController@updateHargaJual')->name('hj.update');
    Route::post('/harga-jual/hapus', $path.'\MasterData\HargaJualController@deleteHargaJual')->name('hj.delete');
    Route::post('/harga-jual/restore', $path.'\MasterData\HargaJualController@restoreHargaJual')->name('hj.restore');
    Route::post('/harga-jual/lihat-history', $path.'\MasterData\HargaJualController@lihatHistoryHargaJual')->name('hj.history');
    Route::match(['get','post'],'/harga-jual/ajax/{cat}', $path.'\MasterData\HargaJualController@callAjax');
    //Golongan
    Route::get('/golongan', $path.'\MasterData\GolonganController@index')->name('golongan.view');
    Route::get('/golongan/golongan-telah-dihapus', $path.'\MasterData\GolonganController@golonganTelahDihapus')->name('golongan.telah_dihapus');
    Route::match(['get', 'post'], '/golongan/tambah', $path.'\MasterData\GolonganController@createGolongan')->name('golongan.create');
    Route::match(['get', 'post'], '/golongan/ubah', $path.'\MasterData\GolonganController@updateGolongan')->name('golongan.update');
    Route::post('/golongan/hapus', $path.'\MasterData\GolonganController@deleteGolongan')->name('golongan.delete');
    Route::post('/golongan/restore', $path.'\MasterData\GolonganController@restoreGolongan')->name('golongan.restore');
    Route::match(['get','post'],'/golongan/ajax/{cat}', $path.'\MasterData\GolonganController@callAjax');
    //Type
    Route::get('/type', $path.'\MasterData\TypeController@index')->name('type.view');
    Route::get('/type/type-telah-dihapus', $path.'\MasterData\TypeController@typeTelahDihapus')->name('type.telah_dihapus');
    Route::post('/type/tambah', $path.'\MasterData\TypeController@createType')->name('type.create');
    Route::match(['get', 'post'], '/type/ubah', $path.'\MasterData\TypeController@updateType')->name('type.update');
    Route::post('/type/hapus', $path.'\MasterData\TypeController@deleteType')->name('type.delete');
    Route::post('/type/restore', $path.'\MasterData\TypeController@restoreType')->name('type.restore');
    Route::post('/type/lihat-history', $path.'\MasterData\TypeController@lihatHistoryType')->name('type.history');
    Route::match(['get','post'],'/type/ajax/{cat}', $path.'\MasterData\TypeController@callAjax');
    //Sub Type
    Route::get('/sub-type', $path.'\MasterData\SubTypeController@index')->name('stype.view');
    Route::get('/sub-type/sub-type-telah-dihapus', $path.'\MasterData\SubTypeController@sTypeTelahDihapus')->name('stype.telah_dihapus');
    Route::post('/sub-type/tambah', $path.'\MasterData\SubTypeController@createSType')->name('stype.create');
    Route::match(['get', 'post'], '/sub-type/ubah', $path.'\MasterData\SubTypeController@updateSType')->name('stype.update');
    Route::post('/sub-type/hapus', $path.'\MasterData\SubTypeController@deleteSType')->name('stype.delete');
    Route::post('/sub-type/restore', $path.'\MasterData\SubTypeController@restoreSType')->name('stype.restore');
    Route::post('/sub-type/lihat-history', $path.'\MasterData\SubTypeController@lihatHistorySType')->name('stype.history');
    Route::match(['get','post'],'/sub-type/ajax/{cat}', $path.'\MasterData\SubTypeController@callAjax');
    Route::get('/sub-type/ajax-select', $path.'\MasterData\SubTypeController@ajaxSelect');
});
