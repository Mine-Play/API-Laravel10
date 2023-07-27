<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Controller;
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


//Protected Routes

// Route::group(["middleware" => ["auth:sanctum"]], function () {

// });





Route::get('/', 'HomeController');
Route::middleware('api')->prefix('auth')->group(function () {
    Route::post('login', 'Auth\LoginController@login');
    /**
     * Authenticated
     */
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', 'Auth\LogoutController@logout');
        Route::post('refresh', 'AuthController@refresh');
    });
    Route::post('register', 'Auth\RegisterController@register');
});

Route::middleware('api')->prefix('email')->group(function () {
    Route::get('/email/verify/{id}/{hash}', 'EmailController@Verify')->middleware(['signed', 'auth:sanctum', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verify/resend', 'EmailController@VerifyResend')->middleware(['auth:sanctum', 'signed', 'throttle:6,1'])->name('verification.send');
});

Route::middleware('api')->group(function () {
    Route::prefix('users')->group(function () {
        Route::middleware('auth:sanctum')->post('/me', 'UserController@me');
        Route::middleware('auth:sanctum')->post('/', 'UserController@me');
        Route::get('/id/{id}', 'UserController@getByID')->whereUuid('id');
        Route::get('/login/{login}', 'UserController@getByLogin');
    });
    Route::prefix('news')->group(function () {
        Route::get('/', 'NewsController@getAll');
        Route::get('/id/{id}', 'NewsController@getByID')->whereUuid('id');
    });
    Route::get('/sliders/{type}', 'SlidersController@getAll');
    Route::prefix('wallets')->group(function () {
        Route::post('/', 'WalletsController@me')->middleware('auth:sanctum');
        Route::post('/me', 'WalletsController@me')->middleware('auth:sanctum');
        Route::get('/wid/{wid}', 'WalletsController@getByWID')->whereUuid('id');
    });
    Route::prefix('roles')->group(function () {
        Route::get('/', 'RolesController@getAll');
        Route::get('/id/{id}', 'RolesController@getByID')->whereUuid('id');
    });
    Route::prefix('servers')->group(function () {
        Route::get('/', 'ServersController@getAll');
        Route::get('/status', 'ServersController@getGlobalOnline');
        Route::get('/slug/{slug}', 'ServersController@getBySlug');
        Route::get('/id/{id}', 'ServersController@getByID')->whereUuid('id');
    });
});
Auth::routes(['verify' => true]);