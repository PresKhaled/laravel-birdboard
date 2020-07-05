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
    Route::get('projects', 'ProjectController@index')->name('projects');

    Route::get('projects/create', 'ProjectController@create')->name('createProject');

    Route::post('projects', 'ProjectController@store')->name('saveProject');

    
    Route::get('project/{project}', 'ProjectController@show')->name('project');

    Route::get('project/{project}/edit', 'ProjectController@edit')->name('editProject');
    
    Route::patch('project/{project}', 'ProjectController@update')->name('updateProject');

    Route::delete('project/{project}', 'ProjectController@destroy')->name('deleteProject');

    /* TODO: To use it,
     *
     * delete all routes name and use action('Controller@action', $wildcardParameters)
     * instead of route('name', $wildcardParameters)
     */
    //Route::resource('projects', 'ProjectsController');


    Route::post('project/{project}/invitation', 'ProjectInvitationController@store')->name('invitation');


    Route::post('project/{project}/task', 'TaskController@store')->name('saveTask');

    Route::patch('project/{project}/task/{task}', 'TaskController@update')->name('updateTask');

    Route::get('project/{project}/task/{task}', 'TaskController@show')->name('task');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
