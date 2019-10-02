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

Auth::routes(); // laravel Authentication

Route::get('/logout-manual', function () 
{
    request()->session()->invalidate();
});

Route::post('/imgs/upload', function()
{
    request()->file('file')->store(
        'images',
        's3'
    );
})->name('upload');

Route::get('/{any}', 'AppController@index')->where('any', '.*');
Route::post('/pw_check', 'PasswordController@check')->name('check');
Route::post('/pw_reset', 'PasswordController@reset')->name('reset');