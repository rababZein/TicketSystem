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
    Route::group(['prefix' => 'permission', 'namespace' => 'API'], function () {
        Route::get('/users/list', 'UsersController@list');
        Route::get('/user/getAllResponsibles', 'UsersController@getAllResponsibles');
        Route::resource('/users', 'UsersController')->except('show', 'create');
    });

    Route::group(['prefix' => 'project'], function () {
        Route::resource('/', 'API\ProjectController')->except('create');
        Route::get('/list', 'API\ProjectController@list');
        Route::get('/{search_key}', 'API\ProjectController@search');
    });

    Route::group(['prefix' => 'ticket'], function () {
        Route::get('/list', 'API\TicketController@list');
        Route::get('/getall', 'API\TicketController@getAll');
        Route::get('/{ticket_id}', 'API\TicketController@show');
        Route::post('/', 'API\TicketController@store');
        Route::patch('/{ticket_id}', 'API\TicketController@update');
        Route::delete('/{ticket_id}', 'API\TicketController@destroy');
    });

    Route::group(['prefix' => 'task'], function () {
        Route::get('/', 'API\TaskController@getAll');
        Route::get('/list', 'API\TaskController@list');
        Route::get('/{task_id}', 'API\TaskController@show');
        Route::post('/{project_id}', 'API\TaskController@store');
        Route::patch('/{task_id}', 'API\TaskController@update');
        Route::patch('/changeStatus/{task_id}', 'API\TaskController@changeStatus');
        Route::delete('/{task_id}', 'API\TaskController@destroy');
    });

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




