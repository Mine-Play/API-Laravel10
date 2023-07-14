<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Controller;
/*
|--------------------------------------------------------------------------
| LAUNCHER Routes
|--------------------------------------------------------------------------
|
| Here is where you can register LAUNCHER routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "launcher" middleware group. Enjoy building your LAUNCHER!
|
*/
Route::middleware('token:launcher')->prefix('user')->group(function () {
    Route::get('/name/{name}', 'Launcher\UserController@getByName');
    Route::get('/login/{name}', 'Launcher\UserController@getByName');
    Route::get('/uuid/{uuid}', 'Launcher\UserController@getByUuid');
});
Route::prefix('auth')->group(function () {
    Route::middleware('auth.jwt')->get('/current', 'Launcher\AuthController@current');
    Route::middleware('token:launcher')->group(function () {
        Route::get('/refresh', 'Launcher\AuthController@refresh');
        Route::get('/authorize', 'Launcher\AuthController@login');
        Route::get('/details', 'Launcher\AuthController@details');
        Route::get('/updateServerIdUrl', 'Launcher\AuthController@updateServer');
        // Route::get('/checkServer', 'Launcher\AuthController@checkServer');
    });
});