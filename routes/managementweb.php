<?php
use Illuminate\Support\Facades\Route;
$path = "App\Http\Controllers";
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
