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
Route::group(['namespace' => 'Frontend'], function () {
    Route::get('/', 'HomeController@index');
    Route::get('/impressum', 'HomeController@impressum')->name('impressum');
    Route::get('/agb', 'HomeController@agb')->name('agb');
});

Route::group(['middleware' => ['auth'], 'namespace' => 'Vue'], function () {
    Route::get('/admin', 'VueController@index')->name('admin');
});


Route::group(['middleware' => ['auth'], 'namespace' => 'API', 'prefix' => 'v-api'], function () {
    // roles
    Route::get('/roles/list', 'RolesController@list');
    Route::get('/roles/getall', 'RolesController@getAll');
    Route::resource('/roles', 'RolesController')->except('show', 'create');

    // permissions
    Route::get('/permissions/list', 'PermissionsController@list');
    Route::get('/permissions/getall', 'PermissionsController@getAll');
    Route::resource('/permissions', 'PermissionsController')->except('show', 'create');

    // users
    Route::get('/user/getAllResponsibles', 'UsersController@getAllResponsibles');
    Route::get('/user/getClientsPaginated', 'UsersController@getClientsPaginated');
    Route::get('/user/getEmployeesPaginated', 'UsersController@getEmployeesPaginated');
    Route::resource('/users', 'UsersController')->except('create');

    // metadata
    Route::resource('/metadata', 'API\MetadataController')->except('create', 'edit');

    // projects
    Route::get('/projects/index', 'ProjectController@view')->name('project.view');
    Route::get('/projects/list', 'ProjectController@list');
    Route::get('/project/getAllByOwner/{owner_id}', 'ProjectController@getAllByOwner');
    Route::get('/clients/{client_id}/projectsNumber', 'ProjectController@getProjectsCountPerClient');
    Route::get('/clients/{client_id}/projects', 'ProjectController@getProjectsPerClient');
    Route::resource('/projects', 'ProjectController')->except('create');

    // tracking tasks
    Route::get('/tracking/timeReporting', 'Tracking_taskController@timeReporting');
    Route::patch('/tracking/{task_id}/{tracking_id}', 'Tracking_taskController@update');
    Route::post('/tracking/{task_id}', 'Tracking_taskController@store');
    Route::delete('/tracking/{task_id}/{tracking_id}', 'Tracking_taskController@destroy');
    Route::get('/tracking/{task_id}', 'Tracking_taskController@tracking');
    Route::get('/tracking/checkTrackingInProgress/{task_id}', 'Tracking_taskController@checkTrackingInProgress');
    Route::get('/tracking/history/{task_id}', 'Tracking_taskController@getHistory');

    // tickets
    Route::get('/projects/{project_id}/tickets/', 'TicketController@getTicketsByProjectId');
    Route::get('/clients/{client_id}/ticketsNumber', 'TicketController@getTicketsCountPerClient');
    Route::get('/clients/{client_id}/tickets', 'TicketController@getTicketsPerClient');
    Route::post('/tickets/{project_id}', 'TicketController@store');
    Route::resource('/tickets', 'TicketController')->except('create');

    // task
    Route::get('/tasks', 'TaskController@index');
    Route::get('/tickets/{ticket_id}/tasks/', 'TaskController@getTasksByTicketId');
    Route::get('/clients/{client_id}/tasksNumber', 'TaskController@getTasksCountPerClient');
    Route::get('/clients/{client_id}/tasks', 'TaskController@getTasksPerClient');
    Route::get('/tasks/filter', 'TaskController@filterTasks');
    Route::get('/tasks/cards', 'TaskController@tasksCard');
    Route::post('/tasks/{project_id}', 'TaskController@store');
    Route::resource('/tasks', 'TaskController')->except('create', 'store', 'index');

    // owner
    Route::get('/owner/getall', 'UsersController@getClients');

    // receipt
    Route::get('/receipts/list', 'ReceiptController@list');
    Route::get('/receipts/getall', 'ReceiptController@getAll');
    Route::resource('/receipts', 'ReceiptController')->except('create', 'store');
    Route::post('/receipts/{project_id}', 'ReceiptController@store');

    // status
    Route::get('/status/getAll', 'StatusController@getAll');
});


Route::get('/{path}', 'Vue\VueController@index')->where('path', '^(?!v-api).*$');
