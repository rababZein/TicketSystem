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