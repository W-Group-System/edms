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



Auth::routes();
Route::group(['middleware' => 'auth'], function () {

    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/request', 'RequestController@index')->name('requests');


    Route::get('/documents', 'DocumentController@index')->name('documents');


    Route::get('/permits', 'PermitController@index')->name('permits');
    Route::post('new-permit', 'PermitController@store')->name('permits');
    Route::post('/upload-permit/{id}', 'PermitController@upload')->name('permits');
    Route::post('/change-department/{id}', 'PermitController@update')->name('permits');


    Route::get('/companies', 'CompanyController@index')->name('settings');
    Route::post('/new-company', 'CompanyController@store')->name('settings');
    Route::post('deactivate-company', 'CompanyController@deactivate')->name('settings');
    Route::post('activate-company', 'CompanyController@activate')->name('settings');

    Route::get('/departments', 'DepartmentController@index')->name('settings');
    Route::post('/new-department', 'DepartmentController@store')->name('settings');
    Route::post('deactivate-department', 'DepartmentController@deactivate')->name('settings');
    Route::post('activate-department', 'DepartmentController@activate')->name('settings');

    //Users
    Route::get('/users', 'UserController@index')->name('settings');
    Route::post('new-account', 'UserController@create')->name('settings');
    Route::post('/change-password/{id}', 'UserController@changepassword')->name('settings');
    Route::post('/edit-user/{id}', 'UserController@edit_user')->name('settings');
    Route::post('deactivate-user', 'UserController@deactivate_user')->name('settings');
    Route::post('activate-user', 'UserController@activate_user')->name('settings');



    Route::get('/logs', 'AuditController@index')->name('reports');
});
