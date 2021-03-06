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

Route::middleware('auth:api')->group(function () 
{
    Route::post('/imgs', 'ImagesController@store');
    Route::patch('/imgs/{id}', 'ImagesController@update');
    Route::delete('/imgs/{id}', 'ImagesController@destroy');
});

Route::get('/imgs', 'ImagesController@index')->name('index');
Route::get('/imgs/{id}', 'ImagesController@show')->name('show');

Route::get('/imgs/user/{id}', 'ImagesController@index_withUser');
Route::get('/tag', 'ImagesController@indexByTag');

Route::post('search', 'SearchController@index');

Route::get('/tags', 'SearchController@all')->name('all');
Route::get('/imgs/{id}/report', 'ImagesController@report')->name('report');