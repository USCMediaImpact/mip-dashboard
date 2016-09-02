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

Route::get('admin/detail', 'SuperAdmin\AccountController@loadClientInfo');
Route::post('admin/detail', 'SuperAdmin\AccountController@saveClientInfo');

// Admin management routes...
Route::get('auth/account/management', 'Auth\AccountController@showAccount');
Route::get('auth/account/all', 'Auth\AccountController@loadAccount');
Route::post('auth/account/invite', 'Auth\AccountController@invite');
Route::get('auth/account/{id}', 'Auth\AccountController@getAccount');
Route::post('auth/account', 'Auth\AccountController@editAccount');
Route::delete('auth/account/{id}', 'Auth\AccountController@removeAccount');

Route::get('auth/detail', 'Auth\AccountController@loadClientInfo');
Route::post('auth/detail', 'Auth\AccountController@saveClientInfo');

// Demo routes...
Route::match(['get', 'post'], '/', 'DashboardController@show');

//google storage static routes no need login
Route::get('/storage/{client_code}/logo', 'StorageController@showLogo');

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
Route::post('/data/users/total_known_users/csv/all', 'DataController@download_All_Users_Total_Known_Users');
Route::post('/data/users/email_newsletter_subscribers', 'DataController@get_Users_Email_Newsletter_Subscribers');
Route::post('/data/users/email_newsletter_subscribers/csv', 'DataController@download_Users_Email_Newsletter_Subscribers');
Route::post('/data/users/email_newsletter_subscribers/csv/all', 'DataController@download_All_Users_Email_Newsletter_Subscribers');
Route::post('/data/users/donors', 'DataController@get_Users_Donors');
Route::post('/data/users/donors/csv', 'DataController@download_Users_Donors');
Route::post('/data/users/donors/csv/all', 'DataController@download_All_Users_Donors');
Route::post('/data/users/members', 'DataController@get_Users_Members');
Route::post('/data/users/members/csv', 'DataController@download_Users_Members');
Route::post('/data/users/members/csv/all', 'DataController@download_All_Users_Members');


Route::match(['get', 'post'], '/data/donations', 'DataController@showDonations');


Route::get('/data/stories', 'DataController@showStories');
Route::post('/data/stories/scroll_depth/{mode}', 'DataController@get_Stories_Scroll_Depth');
Route::post('/data/stories/scroll_depth/{mode}/csv', 'DataController@download_Stories_Scroll_Depth');
Route::post('/data/stories/scroll_depth/{mode}/csv/all', 'DataController@download_All_Stories_Scroll_Depth');
Route::post('/data/stories/time_on_article/{mode}', 'DataController@get_Stories_Time_On_Article');
Route::post('/data/stories/time_on_article/{mode}/csv', 'DataController@download_Stories_Time_On_Article');
Route::post('/data/stories/time_on_article/{mode}/csv/all', 'DataController@download_All_Stories_Time_On_Article');
Route::post('/data/stories/user_interactions', 'DataController@get_Stories_User_Interactions');
Route::post('/data/stories/user_interactions/csv', 'DataController@download_Stories_User_Interactions');
Route::post('/data/stories/user_interactions/csv/all', 'DataController@download_All_Stories_User_Interactions');

Route::get('/data/newsletter', 'DataController@showNewsLetter');
Route::post('/data/newsletter', 'DataController@get_NewsLetter');
Route::post('/data/newsletter/csv', 'DataController@download_NewsLetter');

Route::get('/data/quality', 'DataController@showQuality');
Route::post('/data/quality/ga_vs_gtm', 'DataController@get_Quality_GA_VS_GTM');
Route::post('/data/quality/ga_vs_gtm/csv', 'DataController@download_Quality_GA_VS_GTM');
Route::post('/data/quality/ga_vs_gtm/csv/all', 'DataController@download_All_Quality_GA_VS_GTM');
Route::post('/data/quality/email_subscribers', 'DataController@get_Quality_Email_Subscribers');
Route::post('/data/quality/email_subscribers/csv', 'DataController@download_Quality_Email_Subscribers');
Route::post('/data/quality/email_subscribers/csv/all', 'DataController@download_All_Quality_Email_Subscribers');
Route::post('/data/quality/donors', 'DataController@get_Quality_Donors');
Route::post('/data/quality/donors/csv', 'DataController@download_Quality_Donors');
Route::post('/data/quality/donors/csv/all', 'DataController@download_All_Quality_Donors');
Route::post('/data/quality/total_known_users', 'DataController@get_Quality_Total_Known_Users');
Route::post('/data/quality/total_known_users/csv', 'DataController@download_Quality_Total_Known_Users');
Route::post('/data/quality/total_known_users/csv/all', 'DataController@download_All_Quality_Total_Known_Users');
Route::post('/data/quality/members', 'DataController@get_Quality_Members');
Route::post('/data/quality/members/csv', 'DataController@download_Quality_Members');
Route::post('/data/quality/members/csv/all', 'DataController@download_All_Quality_Members');


Route::get('/analyses', 'AnalysesController@show');
Route::get('/analyses/{guid}', 'AnalysesController@display');
Route::post('/analyses', 'AnalysesController@upload');
Route::post('/analyses/download', 'AnalysesController@download');
Route::post('/analyses/edit', 'AnalysesController@edit');
Route::post('/analyses/delete', 'AnalysesController@delete');

Route::match(['get', 'post'], '/management/data-exception', 'DataExceptionController@show');
Route::get('/management/data-exception/{id}', 'DataExceptionController@get');
Route::post('/management/data-exception/new', 'DataExceptionController@create');
Route::post('/management/data-exception/edit', 'DataExceptionController@edit');
Route::post('/management/data-exception/delete', 'DataExceptionController@delete');
Route::post('/management/data-exception/download', 'DataExceptionController@download');