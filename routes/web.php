<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => 'auth'], function () {
    Route::get('projects', 'ProjectController@index');

    Route::get('projects/create', 'ProjectController@create');

    Route::post('projects', 'ProjectController@store');
    
    Route::get('project/{project}', 'ProjectController@show')->name('project');
    
    Route::patch('project/{project}', 'ProjectController@update')->name('updateProject');

    Route::post('project/{project}/task', 'TaskController@store')->name('saveTask');

    Route::patch('project/{project}/task/{task}', 'TaskController@update')->name('updateTask');

    Route::get('project/{project}/task/{task}', 'TaskController@show')->name('task');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
