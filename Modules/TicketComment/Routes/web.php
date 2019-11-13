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

Route::resource('/ticketComments', 'TicketCommentController')->except('create', 'edit');
Route::get('/commentsPerTicket/{ticket_id}', 'TicketCommentController@getCommentsPerTicket');

