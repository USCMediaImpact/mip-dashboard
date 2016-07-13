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
Route::get('admin/account/management', 'SuperAdmin\AccountController@showPage');
Route::get('admin/account/all', 'SuperAdmin\AccountController@loadAccount');
Route::post('admin/account/invite', 'SuperAdmin\AccountController@invite');
Route::get('admin/account/{id}', 'SuperAdmin\AccountController@getAccount');
Route::post('admin/account', 'SuperAdmin\AccountController@saveAccount');
Route::delete('admin/account/{id}', 'SuperAdmin\AccountController@removeAccount');

Route::get('admin/client/management', 'SuperAdmin\ClientController@showPage');
Route::get('admin/client/all', 'SuperAdmin\ClientController@loadClient');
Route::get('admin/client/{id}', 'SuperAdmin\ClientController@getClient');
Route::post('admin/client', 'SuperAdmin\ClientController@saveClient');
Route::delete('admin/client/{id}', 'SuperAdmin\ClientController@removeClient');

Route::get('admin/client/setting/{id}', 'SuperAdmin\SettingController@showPage');
Route::post('admin/client/setting', 'SuperAdmin\SettingController@save');

// Admin management routes...
Route::get('auth/account/management', 'Auth\AccountController@showAccount');
Route::get('auth/account/all', 'Auth\AccountController@loadAccount');
Route::post('auth/account/invite', 'Auth\AccountController@invite');
Route::get('auth/account/{id}', 'Auth\AccountController@getAccount');
Route::post('auth/account', 'Auth\AccountController@editAccount');
Route::delete('auth/account/{id}', 'Auth\AccountController@removeAccount');

// Demo routes...
Route::match(['get', 'post'], '/', 'DashboardController@showDashboard');

// Main routes...
Route::match(['get', 'post'], '/reports/content', 'ReportsController@showContent');
Route::match(['get', 'post'], '/reports/users', 'ReportsController@showUsers');
Route::match(['get', 'post'], '/reports/donations', 'ReportsController@showDonations');

Route::match(['get', 'post'], '/metrics/content', 'MetricsController@showContent');
Route::match(['get', 'post'], '/metrics/users', 'MetricsController@showUsers');
Route::match(['get', 'post'], '/metrics/donations', 'MetricsController@showDonations');

Route::match(['get', 'post'], '/data/content', 'DataController@showContent');

Route::get('/data/users', 'DataController@showUsers');
Route::post('/data/users/total_known_users', 'DataController@get_Users_Total_Known_Users');
Route::post('/data/users/total_known_users/csv', 'DataController@download_Users_Total_Known_Users');
Route::post('/data/users/email_newsletter_subscribers', 'DataController@get_Users_Email_Newsletter_Subscribers');
Route::post('/data/users/email_newsletter_subscribers/csv', 'DataController@download_Users_Email_Newsletter_Subscribers');
Route::post('/data/users/donors', 'DataController@get_Users_Donors');
Route::post('/data/users/donors/csv', 'DataController@download_Users_Donors');
Route::match(['get', 'post'], '/data/donations', 'DataController@showDonations');
Route::get('/data/stories', 'DataController@showStories');
Route::post('/data/stories/scroll_depth/{mode}', 'DataController@get_Stories_Scroll_Depth');
Route::post('/data/stories/time_on_article/{mode}', 'DataController@get_Stories_Time_On_Article');
Route::post('/data/stories/user_interactions', 'DataController@get_Stories_User_Interactions');
Route::match(['get', 'post'], '/data/quality', 'DataController@showQuality');

