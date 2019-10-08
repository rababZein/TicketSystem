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

Route::group(['middleware' => ['auth'], 'namespace' => 'Vue'], function () {
    Route::get('/', 'VueController@index');
    Route::get('/home', 'VueController@index')->name('home');
});


Route::group(['middleware' => ['auth'], 'namespace' => 'API', 'prefix' => 'v-api'], function () {
    // roles
    Route::get('/roles/list', 'RolesController@list');
    Route::resource('/roles', 'RolesController')->except('show', 'create');

    // permissions
    Route::get('/permissions/list', 'PermissionsController@list');
    Route::get('/permissions/getall', 'PermissionsController@getAllPermissions');
    Route::resource('/permissions', 'PermissionsController')->except('show', 'create');

    // users
    Route::get('/users/list', 'UsersController@list');
    Route::get('/user/getAllResponsibles', 'UsersController@getAllResponsibles');
    Route::resource('/users', 'UsersController')->except('show', 'create');

    // projects
    Route::get('/projects/index', 'ProjectController@view')->name('project.view');
    Route::get('/project/getall', 'ProjectController@getAll');
    Route::get('/project/getAllByOwner/{owner_id}', 'ProjectController@getAllByOwner');
    Route::resource('/projects', 'ProjectController')->except('create');

    // tracking tasks
    Route::post('/tracking', 'Tracking_taskController@store');
    Route::patch('/tracking/{task_id}', 'Tracking_taskController@update');
    Route::delete('/tracking/{task_id}', 'Tracking_taskController@destroy');
    Route::get('/tracking/{task_id}', 'Tracking_taskController@tracking');
    Route::get('/tracking/history/{task_id}', 'Tracking_taskController@getHistory');

    // tickets
    Route::get('/tickets/getall', 'TicketController@getAll');
    Route::resource('/tickets', 'TicketController')->except('create');

    // task
    Route::get('/tasks/getall', 'TaskController@getAll');
    Route::resource('/tasks', 'TaskController')->except('create');

    // owner
    Route::get('/owner/getall', 'UsersController@getClients');

    // receipt
    Route::get('/receipts/getall', 'ReceiptController@getAll');
    Route::resource('/receipts', 'ReceiptController')->except('create');
});

Route::get('/{path}', 'Vue\VueController@index')->where('path', '^(?!v-api).*$');
