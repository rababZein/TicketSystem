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
Route::get('open', 'DataController@open');

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('user', 'API\AuthController@getAuthenticatedUser');
    // Route::get('closed', 'DataController@closed');
});

Route::group(['prefix' => 'admin', 'middleware' => ['jwt.verify']], function() {
    Route::group(['prefix' => 'project'], function () {
        Route::get('/', 'API\ProjectController@index');
        Route::post('/', 'API\ProjectController@store');
        Route::patch('/{project_id}', 'API\ProjectController@update');
        Route::delete('/{project_id}', 'API\ProjectController@destroy');
    });

    Route::group(['prefix' => 'ticket'], function () {
        Route::get('/', 'API\TicketController@index');
        Route::post('/', 'API\TicketController@store');
        Route::patch('/{ticket_id}', 'API\TicketController@update');
        Route::delete('/{ticket_id}', 'API\TicketController@destroy');
    });

    Route::group(['prefix' => 'task'], function () {
        Route::get('/', 'API\TaskController@index');
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
