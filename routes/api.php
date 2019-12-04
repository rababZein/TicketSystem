<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// Route::middleware('auth:api')->get('users', function (Request $request) {
//     return $request->user();
// });

// Route::apiResources(['users' => 'API\UsersController']);


Route::post('register', 'API\AuthController@register');
Route::post('login', 'API\AuthController@authenticate');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', 'API\AuthController@getAuthenticatedUser');
});

Route::group(['prefix' => 'admin', 'middleware' => ['jwt.verify']], function() {
    // roles
    Route::group(['prefix' => 'role', 'namespace' => 'API'], function () {
        Route::get('/list', 'RolesController@list');
        Route::get('/getall', 'RolesController@getAll');
        Route::resource('/roles', 'RolesController')->except('show', 'create');
    });

    // permissions
    Route::group(['prefix' => 'permission', 'namespace' => 'API'], function () {
        Route::get('/permissions/list', 'PermissionsController@list');
        Route::get('/permissions/getall', 'PermissionsController@getAll');
        Route::resource('/permissions', 'PermissionsController')->except('show', 'create');
    });

    // users
    Route::group(['prefix' => 'users', 'namespace' => 'API'], function () {
        Route::get('/list', 'UsersController@list');
        Route::get('/getAllResponsibles', 'UsersController@getAllResponsibles');
        Route::get('/getClientsPaginated', 'UsersController@getClientsPaginated');
        Route::get('/getEmployeesPaginated', 'UsersController@getEmployeesPaginated');
        Route::resource('/', 'UsersController')->except('show', 'create');
    });

    Route::group(['prefix' => 'project'], function () {
        Route::resource('/', 'API\ProjectController')->except('create');
        Route::get('/list', 'API\ProjectController@list');
        Route::get('/{search_key}', 'API\ProjectController@search');
    });
    Route::get('/clients/{client_id}/projectsNumber', 'API\ProjectController@getProjectsCountPerClient');
    Route::get('/clients/{client_id}/projects', 'API\ProjectController@getProjectsPerClient');

    Route::group(['prefix' => 'ticket'], function () {
        Route::get('/list', 'API\TicketController@list');
        Route::get('/getall', 'API\TicketController@getAll');
        Route::get('/{ticket_id}', 'API\TicketController@show');
        Route::post('/{project_id}', 'API\TicketController@store');
        Route::patch('/{ticket_id}', 'API\TicketController@update');
        Route::delete('/{ticket_id}', 'API\TicketController@destroy');
    });
    Route::get('/projects/{project_id}/tickets', 'API\TicketController@getTicketsByProjectId');
    Route::get('/clients/{client_id}/ticketsNumber', 'API\TicketController@getTicketsCountPerClient');
    Route::get('/clients/{client_id}/tickets', 'API\TicketController@getTicketsPerClient');

    Route::group(['prefix' => 'task'], function () {
        Route::get('/', 'API\TaskController@getAll');
        Route::get('/list', 'API\TaskController@list');
        Route::get('/filterTasks', 'API\TaskController@filterTasks');
        Route::get('/{task_id}', 'API\TaskController@show');
        Route::post('/{project_id}', 'API\TaskController@store');
        Route::patch('/{task_id}', 'API\TaskController@update');
        Route::delete('/{task_id}', 'API\TaskController@destroy');
    });
    Route::get('/clients/{client_id}/tasksNumber', 'API\TaskController@getTasksCountPerClient');
    Route::get('/clients/{client_id}/tasks', 'API\TaskController@getTasksPerClient');

    Route::group(['prefix' => 'receipt'], function () {
        Route::get('/', 'API\ReceiptController@getAll');
        Route::get('/list', 'API\ReceiptController@list');
        Route::post('/{project_id}', 'API\ReceiptController@store');
        Route::patch('/{receipt_id}', 'API\ReceiptController@update');
        Route::delete('/{receipt_id}', 'API\ReceiptController@destroy');
    });
});

// tracking tasks 
Route::group(['prefix' => 'tracking', 'middleware' => ['jwt.verify'], 'namespace' => 'API'], function () {
    Route::get('/timeReporting', 'Tracking_taskController@timeReporting');
    Route::post('/{task_id}', 'Tracking_taskController@store');
    Route::patch('/{task_id}', 'Tracking_taskController@update');
    Route::delete('/{task_id}', 'Tracking_taskController@destroy');
    Route::get('/{task_id}', 'Tracking_taskController@tracking');
    Route::get('/checkTrackingInProgress/{task_id}', 'Tracking_taskController@checkTrackingInProgress');
    Route::get('/history/{task_id}', 'Tracking_taskController@getHistory');
});

Route::group(['prefix' => 'status', 'middleware' => ['jwt.verify']], function () {
    Route::get('/getAll', 'API\StatusController@getAll');
});
