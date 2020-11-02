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

Route::post('register', 'V1\UserController@register');
Route::post('login', 'V1\UserController@login');
Route::get('book', 'V1\BookController@book');
//
Route::get('bookall', 'V1\BookController@bookAuth')->middleware('jwt.verify');
Route::get('user', 'V1\UserController@getAuthenticatedUser')->middleware('jwt.verify');
