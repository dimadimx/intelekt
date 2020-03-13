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

Auth::routes(['verify' => true]);

Route::get('/', 'HomeController@index')->middleware('verified');

Route::get('/home', 'HomeController@index')->middleware('verified');

Route::resource('users', 'UserController')->middleware('auth');

Route::get('clients/sync', 'ClientController@sync')->name('clients.sync')->middleware('auth');

Route::resource('clients', 'ClientController')->middleware('auth');

Route::post('clientStatistics/sync', 'ClientStatisticController@syncSessions')->name('clientStatistics.syncSessions')->middleware('auth');

Route::resource('clientStatistics', 'ClientStatisticController')->middleware('auth');

