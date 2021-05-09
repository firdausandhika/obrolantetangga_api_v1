<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('copyToGoogle', 'V1\WilayahController@copyToGoogle')->name('copyToGoogle');



Route::fallback(function () {
  return response()->json(['success'=>false, 'user'=>null,'request'=>null, 'msg' =>'Not Found'], 404);
});
