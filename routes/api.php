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
    Route::group(['prefix' => 'project'], function () {
        Route::get('/', 'API\ProjectController@index');
        Route::post('/', 'API\ProjectController@store');
        Route::patch('/{project_id}', 'API\ProjectController@update');
        Route::delete('/{project_id}', 'API\ProjectController@destroy');
        Route::get('/{search_key}', 'API\ProjectController@search');
    });

    Route::group(['prefix' => 'ticket'], function () {
        Route::get('/', 'API\TicketController@index');
        Route::get('/{ticket_id}', 'API\TicketController@show');
        Route::post('/', 'API\TicketController@store');
        Route::patch('/{ticket_id}', 'API\TicketController@update');
        Route::delete('/{ticket_id}', 'API\TicketController@destroy');
    });

    Route::group(['prefix' => 'task'], function () {
        Route::get('/', 'API\TaskController@index');
        Route::get('/{task_id}', 'API\TaskController@show');
        Route::post('/', 'API\TaskController@store');
        Route::patch('/{task_id}', 'API\TaskController@update');
        Route::delete('/{task_id}', 'API\TaskController@destroy');
    });

    Route::group(['prefix' => 'receipt'], function () {
        Route::get('/', 'API\ReceiptController@index');
        Route::post('/', 'API\ReceiptController@store');
        Route::patch('/{receipt_id}', 'API\ReceiptController@update');
        Route::delete('/{receipt_id}', 'API\ReceiptController@destroy');
    });
});

// tracking tasks 
Route::group(['prefix' => 'tracking', 'middleware' => ['jwt.verify'], 'namespace' => 'API'], function () {
    Route::post('/', 'Tracking_taskController@store');
    Route::patch('/{task_id}', 'Tracking_taskController@update');
    Route::delete('/{task_id}', 'Tracking_taskController@destroy');
    Route::get('/{task_id}', 'Tracking_taskController@tracking');
});




