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

Route::group(['middleware' => ['auth'], 'namespace' => 'Admin'], function() {
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('/users', 'UsersController');
    Route::resource('/settings', 'UsersController');
    Route::resource('/permissions', 'PermissionsController');  
    Route::resource('/roles', 'RolesController');  
      
});
// Route::get('/{path}','HomeController@index')->where( 'path', '^(?!api).*$' );
