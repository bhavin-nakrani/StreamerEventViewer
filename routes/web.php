<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*Route::get('/', function () {
    return view('welcome');
});*/

Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::post('home/create', 'HomeController@store')->name('stream_create');

Route::get('/streams', 'HomeController@stream')->name('stream');
Route::get('/streams/{id}/detail', 'HomeController@detail')->name('stream_detail');

Route::get('login/twitch', 'Auth\LoginController@redirectToProvider')->name('twitch_login');
Route::get('login/twitch/callback', 'Auth\LoginController@handleProviderCallback')->name('twitch_callback');
