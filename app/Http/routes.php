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

// Super Admin management routes...
Route::get('admin/client', 'ClientController@showPage');
Route::get('admin/client/all', 'ClientController@loadClient');
Route::get('admin/client/{id}', 'ClientController@getClient');
Route::post('admin/client', 'ClientController@saveClient');
Route::delete('admin/client/{id}', 'ClientController@removeClient');

// Account management routes...
Route::get('auth/account/management', 'Auth\AccountController@showAccount');
Route::get('auth/account/all', 'Auth\AccountController@loadAccount');
Route::post('auth/account/invite', 'Auth\AccountController@invite');
Route::get('auth/account/{id}', 'Auth\AccountController@getAccount');
Route::post('auth/account', 'Auth\AccountController@editAccount');
Route::delete('auth/account/{id}', 'Auth\AccountController@removeAccount');

// Demo routes...
Route::get('/', 'DashboardController@showDashboard');

// Main routes...
Route::get('/reports/content', 'ReportsController@showContent');
Route::get('/reports/users', 'ReportsController@showUsers');
Route::get('/reports/donations', 'ReportsController@showDonations');

Route::get('/metrics/content', 'MetricsController@showContent');
Route::get('/metrics/users', 'MetricsController@showUsers');
Route::get('/metrics/donations', 'MetricsController@showDonations');

Route::get('/data/content', 'DataController@showContent');
Route::get('/data/users', 'DataController@showUsers');
Route::get('/data/donations', 'DataController@showDonations');
Route::get('/data/quality', 'DataController@showQuality');

