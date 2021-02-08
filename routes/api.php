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

Route::get('firebase', 'V1\FirebaseController@index');

Route::name('v1.')->prefix('v1')->group(function() {
  Route::post('register', 'V1\AuthController@register')->name('register');
  Route::post('login', 'V1\AuthController@login')->name('login');
  Route::post('forgot', 'V1\AuthController@forgot')->name('forgot');
  Route::post('otp', 'V1\AuthController@otp')->name('otp');
  Route::post('resend_otp', 'V1\AuthController@resend_otp')->name('resend_otp');
  // Route::get('user', 'V1\AuthController@getAuthenticatedUser')->middleware('jwt.verify');

  Route::name('wilayah.')->prefix('wilayah')->group(function() {
    Route::get('get_provinsi', 'V1\WilayahController@get_provinsi');
    Route::get('get_kota/{kode}', 'V1\WilayahController@get_kota');
    Route::get('get_kecamatan/{kode}', 'V1\WilayahController@get_kecamatan');
    Route::get('get_kelurahan/{kode}', 'V1\WilayahController@get_kelurahan');
  });


  Route::resource('obrolan', 'V1\ObrolanController');

  Route::resource('like', 'V1\LikeController')->only(['store']);
  Route::resource('dislike', 'V1\DislikeController')->only(['store']);
  Route::resource('report', 'V1\ReportController');
  Route::resource('report_iklan_baris', 'V1\ReportIklanBarisController');

  Route::post('cover', 'V1\CoverController@store')->name('cover');
  Route::post('avatar', 'V1\AvatarController@store')->name('avatar');

  Route::resource('kategori', 'V1\KategoriController')->only(['index']);
  Route::resource('kategori_report', 'V1\KategoriReportController')->only(['index']);

  Route::get('trending', 'V1\TrendingController@index')->name('trending');

  // Route::resource('report', 'V1\ReportController');

  Route::resource('iklan_baris', 'V1\IklanBarisController');
  Route::resource('komentar', 'V1\KomentarController');
  Route::resource('visit', 'V1\VisitController');

  Route::resource('profil', 'V1\ProfilController');

  Route::post('setting/change_password', 'V1\SettingController@change_password')->name('change_password');
  Route::post('setting/change_location', 'V1\SettingController@change_location')->name('change_location');
  Route::post('setting/change_info', 'V1\SettingController@change_info')->name('change_info');
  Route::post('setting/change_token_firebase', 'V1\SettingController@change_token_firebase')->name('change_token_firebase');
  Route::resource('tetangga', 'V1\TetanggaController');


});
