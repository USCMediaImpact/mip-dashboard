<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');

Route::get('auth/logout', 'Auth\AuthController@getLogout');

Route::get('auth/reset', 'Auth\PasswordController@getEmail');
Route::post('auth/reset', 'Auth\PasswordController@postEmail');
Route::get('auth/reset/password/{token}', 'Auth\PasswordController@getReset');
Route::post('auth/reset/password', 'Auth\PasswordController@postReset');

// Registration routes...
Route::get('auth/register', 'Auth\AuthController@getRegister');
Route::post('auth/register', 'Auth\AuthController@postRegister');

// Account management routes...
Route::get('auth/account/management', 'Auth\AccountController@showAccount');
Route::get('auth/account/all', 'Auth\AccountController@loadAccount');
Route::post('auth/account/invite', 'Auth\AccountController@invite');
Route::get('auth/account/{id}', 'Auth\AccountController@getAccount');
Route::put('auth/account', 'Auth\AccountController@editAccount');
Route::delete('auth/account/{id}', 'Auth\AccountController@removeAccount');

// Dashboard routes...
Route::get('/', 'DashboardController@showDataFromMySql');
Route::get('mysql', 'DashboardController@showDataFromMySql');
Route::get('bigquery', 'DashboardController@showDataFromBigQuery');
