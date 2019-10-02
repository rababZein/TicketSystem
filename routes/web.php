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

Route::group(['middleware' => ['auth'],'namespace' => 'API'], function() {
    Route::get('/', 'HomeController@index');
    Route::get('/home', 'HomeController@index')->name('home');

    // roles
    Route::get('/roles/list', 'RolesController@list');
    Route::resource('/roles', 'RolesController')->except('show', 'create');
    
    // permissions
    Route::get('/permissions/list', 'PermissionsController@list');
    Route::get('/permissions/getall', 'PermissionsController@getAllPermissions');
    Route::resource('/permissions', 'PermissionsController')->except('show', 'create');

    // users
    Route::get('/users/list', 'UsersController@list');
    Route::resource('/users', 'UsersController')->except('show', 'create');

    // projects
    Route::get('/projects/index', 'ProjectController@view')->name('project.view');
    Route::resource('/projects', 'ProjectController')->except('create');

});


// Route::get('/{path}','HomeController@index')->where( 'path', '^(?!api).*$' );
