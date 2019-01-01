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
// Main page
Route::get('/', 'HomeController@welcome')->name('welcome')->middleware('guest');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/home', 'HomeController@index')->name('overview');
    Route::resource('/users', 'UserController');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
