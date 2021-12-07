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

Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');

Route::group(['middleware' => 'auth:api'], function()
{
    Route::get('buku', 'Api\BukuController@index');
    // ->middleware('verified');
    Route::get('buku/{id}', 'Api\BukuController@show');
    Route::post('buku', 'Api\BukuController@store');
    Route::put('buku/{id}', 'Api\BukuController@update');
    Route::delete('buku/{id}', 'Api\BukuController@destroy');
});

Route::group(['middleware' => 'auth:api'], function()
{
    Route::get('peminjaman', 'Api\PeminjamanController@index');
    Route::get('peminjaman/{id}', 'Api\PeminjamanController@show');
    Route::get('peminjaman/get/{id}', 'Api\PeminjamanController@showByUser');
    Route::post('peminjaman', 'Api\PeminjamanController@store');
    Route::put('peminjaman/{id}', 'Api\PeminjamanController@update');
    Route::delete('peminjaman/{id}', 'Api\PeminjamanController@destroy');
});

Route::group(['middleware' => 'auth:api'], function()
{
    Route::get('donasi', 'Api\DonasiController@index');
    Route::get('donasi/{id}', 'Api\DonasiController@show');
    Route::get('donasi/get/{id}', 'Api\DonasiController@showByUser');
    Route::post('donasi', 'Api\DonasiController@store');
    Route::put('donasi/{id}', 'Api\DonasiController@update');
    Route::delete('donasi/{id}', 'Api\DonasiController@destroy');
});

Route::group(['middleware' => 'auth:api'], function()
{
    Route::get('reservasi', 'Api\ReservasiController@index');
    Route::get('reservasi/{id}', 'Api\ReservasiController@show');
    Route::get('reservasi/get/{id}', 'Api\ReservasiController@showByUser');
    Route::post('reservasi', 'Api\ReservasiController@store');
    Route::put('reservasi/{id}', 'Api\ReservasiController@update');
    Route::delete('reservasi/{id}', 'Api\ReservasiController@destroy');
});

Route::group(['middleware' => 'auth:api'], function()
{
    Route::get('user', 'Api\UserController@index');
    Route::get('user/{id}', 'Api\UserController@show');
    Route::post('user', 'Api\UserController@store');
    Route::put('user/{id}', 'Api\UserController@update');
    Route::delete('user/{id}', 'Api\UserController@destroy');
});

Route::get('email/verify/{id}', 'VerificationApiController@verify')->name('verificationapi.verify');
Route::get('email/resend', 'VerificationApiController@resend')->name('verificationapi.resend');