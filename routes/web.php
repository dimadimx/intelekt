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

Route::get('/', function () {
    return redirect('/home');
});

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->middleware('verified');

Route::resource('users', 'UserController')->middleware('auth');

Route::get('clients/sync', 'ClientController@sync')->name('clients.sync')->middleware('auth');

Route::resource('clients', 'ClientController')->middleware('auth');

Route::resource('clientStatistics', 'ClientStatisticController')->middleware('auth');

