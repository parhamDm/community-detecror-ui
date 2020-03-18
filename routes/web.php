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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::resource('graph', 'GraphController');
Route::get('/community/compare/{graph}', 'GraphController@compare')->name('compare');;
Route::get('/community/getImage/{path}', 'GraphController@getImage')->name('imageuri');
Route::get('/graph/getFile/{path}', 'GraphController@getFile')->name('fileuri');
Route::get('/graph/destroy/{path}', 'GraphController@destroy')->name('remove_graph');
