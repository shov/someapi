<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'v1'], function () {
    Route::group(['as' => 'user::'], function () {

        Route::post('/login',
            'AuthController@login')
            ->name('login');

        Route::post('/logout',
            'AuthController@logout')
            ->name('logout');
    });

    Route::group(['as' => 'post::'], function () {
        Route::get('/posts',
            'PostController@list')
            ->name('list');

        Route::get('/posts/{id}',
            'PostController@get')
            ->where('id', '\d+')
            ->name('get');

        Route::post('/posts/{id}',
            'PostController@create')
            ->where('id', '\d+')
            ->name('create');

        Route::put('/posts/{id}',
            'PostController@update')
            ->where('id', '\d+')
            ->name('update');

        Route::delete('/posts/{id}',
            'PostController@delete')
            ->where('id', '\d+')
            ->name('delete');
    });

    Route::group(['as' => 'category::'], function () {
        Route::get('/categories/{id}',
            'CategoryController@get')
            ->where('id', '\d+')
            ->name('get');

        Route::post('/categories/{id}',
            'CategoryController@create')
            ->where('id', '\d+')
            ->name('create');

        Route::put('/categories/{id}',
            'CategoryController@update')
            ->where('id', '\d+')
            ->name('update');

        Route::delete('/categories/{id}',
            'CategoryController@delete')
            ->where('id', '\d+')
            ->name('delete');
    });
});