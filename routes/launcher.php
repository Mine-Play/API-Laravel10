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
    Route::get('/name/{name}', 'UserController@getByName');
    Route::get('/login/{name}', 'UserController@getByName');
    Route::get('/uuid/{uuid}', 'UserController@getByUuid');
});
Route::prefix('auth')->group(function () {
    Route::middleware('auth:sanctum')->get('/current', 'AuthController@current');
    Route::middleware('token:launcher')->group(function () {
        Route::post('/refresh', 'AuthController@refresh');
        Route::post('/authorize', 'AuthController@login');
        Route::get('/details', 'AuthController@details');
        Route::post('/joinServer', 'AuthController@joinServer');
        Route::post('/checkServer', 'AuthController@checkServer');
    });
});