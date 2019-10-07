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

    // tracking tasks
    Route::group(['prefix' =>'tracking', 'middleware' => ['auth'],'namespace' => 'API'], function() {
        Route::post('/', 'Tracking_taskController@store');
        Route::patch('/{task_id}', 'Tracking_taskController@update');
        Route::delete('/{task_id}', 'Tracking_taskController@destroy');
        Route::get('/{task_id}', 'Tracking_taskController@tracking');
    });
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
    Route::get('/getAllByOwner/{owner_id}', 'API\ProjectController@getAllByOwner');
});

Route::group(['prefix' => 'owner', 'middleware' => ['auth']], function () {
    Route::get('/getall', 'API\UsersController@getClients');
});

Route::group(['prefix' => 'user', 'middleware' => ['auth']], function () {
    Route::get('/getAllResponsibles', 'API\UsersController@getAllResponsibles');
});

Route::group(['prefix' => 'task', 'middleware' => ['auth']], function () {
    Route::get('/',[
        'as' => 'tasks.index',
        'uses' =>'API\TaskController@index'
    ]);
    Route::get('/getall', 'API\TaskController@getAll');
    Route::post('/', 'API\TaskController@store');
    Route::patch('/{task_id}', 'API\TaskController@update');
    Route::delete('/{task_id}', 'API\TaskController@destroy');
});

Route::group(['prefix' => 'receipt', 'middleware' => ['auth']], function () {
    Route::get('/',[
        'as' => 'receipts.index',
        'uses' =>'API\ReceiptController@index'
    ]);
    Route::get('/getall', 'API\ReceiptController@getAll');
    Route::post('/', 'API\ReceiptController@store');
    Route::patch('/{task_id}', 'API\ReceiptController@update');
    Route::delete('/{task_id}', 'API\ReceiptController@destroy');
});

