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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/imgs', 'ImagesController@store');
Route::get('/imgs/{id}', 'ImagesController@show');
Route::patch('/imgs/{id}', 'ImagesController@update');
Route::delete('/imgs/{id}', 'ImagesController@destroy');