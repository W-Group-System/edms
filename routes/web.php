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


    Route::get('/companies', 'CompanyController@index')->name('settings');

    Route::get('/departments', 'DepartmentController@index')->name('settings');

    //Users
    Route::get('/users', 'UserController@index')->name('settings');
    Route::post('new-account','UserController@create')->name('settings');

    Route::get('/logs', 'AuditController@index')->name('reports');
});
