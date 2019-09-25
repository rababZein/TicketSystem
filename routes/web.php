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

Route::group(['middleware' => ['auth']], function() {
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('/users', 'admin\UsersController');
    Route::resource('/settings', 'admin\UsersController');
    Route::resource('/permissions', 'admin\PermissionsController');  
    Route::resource('/roles', 'admin\RolesController');  
      
});
// Route::get('/{path}','HomeController@index')->where( 'path', '^(?!api).*$' );
