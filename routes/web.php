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

Route::group(['prefix' => 'ticket', 'middleware' => ['auth']], function () {
    Route::get('/',[
        'as' => 'tickets.index',
        'uses' =>'API\TicketController@index'
    ]);
    Route::get('/getall', 'API\TicketController@getAll');
    Route::post('/', 'API\TicketController@store');
    Route::patch('/{ticket_id}', 'API\TicketController@update');
    Route::delete('/{ticket_id}', 'API\TicketController@destroy');
});

Route::group(['prefix' => 'project', 'middleware' => ['auth']], function () {
    Route::get('/getall', 'API\ProjectController@getAll');
});