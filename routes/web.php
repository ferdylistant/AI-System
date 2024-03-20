<?php

use App\Models\Naskah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PusherController;
use App\Http\Controllers\WebSocketController;

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
Route::get('/check-authentication', $path . '\ApiController@checkAuth');
Route::get('login', $path . '\AuthController@login')->name('login');
Route::post('do-login', $path . '\AuthController@doLogin');
Route::post('/forgot-password', $path . '\AuthController@submitForgetPasswordForm')->name('password.email');
Route::get('/reset-password/{token}', function ($token, Request $request) {
    return view('reset_password', ['token' => $token, 'email' => $request->email, 'title' => 'Reset Password']);
})->name('password.reset');
Route::post('reset-password', $path . '\AuthController@submitResetPasswordForm')->name('reset.password.post');

Route::middleware(['auth'])->group(function () use ($path) {
    Route::post('/pusher/user-auth', [PusherController::class, 'pusherAuth']);
    Route::get('tes-socket', [WebSocketController::class, 'index']);
    // Manajemen User
    require __DIR__ . '/managementweb.php';
    //Master Data
    require __DIR__ . '/masterdata.php';
    //Penerbitan
    require __DIR__ . '/penerbitan.php';
    //Jasa Cetak
    require __DIR__ . '/jasacetak.php';
    //Produksi
    require __DIR__ . '/produksi.php';
    //Penjualan Dan Stok
    require __DIR__ . '/penjualanstok.php';
    // Purchasing
    require __DIR__ . '/purchasing.php';
    //API
    Route::post('/update-status-activity', $path . '\ApiController@updateStatusActivity');
    Route::get('/get-layout', $path . '\ApiController@getPosisiLayout');
    Route::get('/list/{list}', $path . '\ApiController@requestAjax');
    Route::post('/update-tanggal-upload-ebook', $path . '\ApiController@updateTanggalUploadEbook');
    Route::post('/update-tanggal-jadi-cetak', $path . '\ApiController@updateTanggalJadiCetak');
    Route::post('/update-jumlah-cetak', $path . '\ApiController@updateJumlahCetak');
    // Route::get('/batal/pending/order-cetak/{id}', $path.'\Produksi\ProduksiController@batalPendingOrderCetak');
    Route::get('/batal/pending/order-ebook/{id}', $path . '\Produksi\EbookController@batalPendingOrderEbook');
    Route::post('/logout', $path . '\AuthController@logout');
    Route::get('/', $path . '\HomeController@index');
    Route::post('import-db', $path . '\HomeController@importDB');

    /* Home */
    Route::match(['get', 'post'], '/api/home/{cat}', $path . '\HomeController@apiAjax');
    Route::post('home/test', $path . '\HomeController@ajaxTest');
    Route::post('home/penerbitan/{cat}', $path . '\HomeController@ajaxPenerbitan');

    Route::prefix('timeline')->group(function () use ($path) {
        Route::post('/{cat}', $path . '\Penerbitan\TimelineController@index');
    });

    // Notification
    Route::prefix('notification')->group(function () use ($path) {
        Route::post('/', $path . '\NotificationController@index');
        Route::get('/view-all', $path . '\NotificationController@viewAll')->name('notification.view_all');
    });
    //Setting
    Route::prefix('setting')->group(function () use ($path) {
        Route::get('/', $path . '\ManWeb\SettingController@index')->name('setting.view');
        Route::match(['get', 'post'], '/{act}/{type}/{id?}', $path . '\ManWeb\SettingController@crudSetting');
    });

    Route::get('search', function () {
        $query = ''; // <-- Change the query for testing.

        $articles = Naskah::search($query)->get();

        return $articles;
    });
});
// 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
