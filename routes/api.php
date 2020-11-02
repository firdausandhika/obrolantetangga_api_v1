<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
  Route::post('register', 'V1\AuthController@register');
  Route::post('login', 'V1\AuthController@login');
  Route::post('otp', 'V1\AuthController@otp');
  // Route::get('book', 'V1\BookController@book');
  //
  // Route::get('bookall', 'V1\BookController@bookAuth')->middleware('jwt.verify');
  // Route::get('user', 'V1\AuthController@getAuthenticatedUser')->middleware('jwt.verify');
});
