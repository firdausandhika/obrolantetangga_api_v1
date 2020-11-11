<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::fallback(function () {
  return response()->json(['success'=>false, 'user'=>null,'request'=>null, 'msg' =>'Not Found'], 404);
});

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/



Route::name('v1.')->prefix('v1')->group(function() {
  Route::post('register', 'V1\AuthController@register')->name('register');
  Route::post('login', 'V1\AuthController@login')->name('login');
  Route::post('forgot', 'V1\AuthController@forgot')->name('forgot');
  Route::post('otp', 'V1\AuthController@otp')->name('otp');
  // Route::get('user', 'V1\AuthController@getAuthenticatedUser')->middleware('jwt.verify');

  Route::name('wilayah.')->prefix('wilayah')->group(function() {
    Route::get('get_provinsi', 'V1\WilayahController@get_provinsi');
    Route::get('get_kota/{kode}', 'V1\WilayahController@get_kota');
    Route::get('get_kecamatan/{kode}', 'V1\WilayahController@get_kecamatan');
    Route::get('get_kelurahan/{kode}', 'V1\WilayahController@get_kelurahan');
  });


  Route::resource('obrolan', 'V1\ObrolanController');
  Route::resource('komentar', 'V1\KomentarController');
  Route::resource('like', 'V1\LikeController')->only(['store']);
  Route::resource('dislike', 'V1\DislikeController')->only(['store']);
  Route::resource('report', 'V1\ReportController');

  Route::post('cover', 'V1\CoverController@store')->name('cover');
  Route::post('avatar', 'V1\AvatarController@store')->name('avatar');

  Route::resource('kategori', 'V1\KategoriController')->only(['index']);

  Route::get('trending', 'V1\TrendingController@index')->name('trending');

  // Route::resource('report', 'V1\ReportController');


});
