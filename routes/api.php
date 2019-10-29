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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('users', 'UserController@getusers');
Route::get('users/{email}', 'UserController@singleuser');
Route::put('users', 'UserController@passwordChange');
Route::post('users/signup', 'UserController@signup');
Route::post('users/login', 'UserController@login');

Route::get('questions', 'QuestionController@index');
Route::post('questions', 'QuestionController@create');
Route::delete('questions', 'QuestionController@destroy');
Route::put('questions', 'QuestionController@edit');

Route::post('answers', 'AnswerController@store');
Route::get('answers/{email}', 'AnswerController@show');
Route::get('answers/admin/{email}', 'AnswerController@getsingleanwer');
Route::get('answers', 'AnswerController@getanswers');
Route::put('answers', 'AnswerController@edit');
