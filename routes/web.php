<?php
use Illuminate\Support\Facades\Storage;

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
    $uploaded = request()->file('file')->store(
        'images',
        's3'
    );

    return [ 
        'url' => 'https://tulbooru.s3.ap-northeast-2.amazonaws.com/' . $uploaded,
    ];
});

Route::post('/imgs/upload/thumb', function()
{
    $uploaded = request()->file('file')->store(
        'thumbnail',
        's3'
    );

    return [ 
        'url' => 'https://tulbooru.s3.ap-northeast-2.amazonaws.com/' . $uploaded,
    ];
});

Route::get('/{any}', 'AppController@index')->where('any', '.*');
Route::get('/imgs/tags', 'ImagesController@index_withTags');
Route::post('/pw_check', 'PasswordController@check')->name('check');
Route::post('/pw_reset', 'PasswordController@reset')->name('reset');