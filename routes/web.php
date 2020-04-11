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

Route::get('clients/updateSignal/{id}', 'ClientController@updateSignal')->name('clients.updateSignal')->middleware('auth');

Route::resource('clients', 'ClientController')->middleware('auth');

Route::post('clientStatistics/sync', 'ClientStatisticController@syncSessions')->name('clientStatistics.syncSessions')->middleware('auth');

Route::resource('clientStatistics', 'ClientStatisticController')->middleware('auth');

Route::get('jobs', 'JobController@index')->name('JobController.index')->middleware('auth');

Route::post('jobs/lists', 'JobController@ajaxUpdate')->name('JobController.ajaxUpdate')->middleware('auth');

Route::get('clientSignals/updates', 'ClientSignalController@updateSignals')->name('clientSignals.updateSignals')->middleware('auth');

Route::resource('clientSignals', 'ClientSignalController')->middleware('auth');
