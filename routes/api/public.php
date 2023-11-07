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
        //Route::post('refresh', 'AuthController@refresh');
    });
    Route::post('register', 'Auth\RegisterController@register');
    Route::prefix('restore')->group(function() {
        Route::post('send', 'Auth\RestoreController@send');
        Route::post('verify', 'Auth\RestoreController@verify')->middleware(['throttle:6,1']);
        Route::post('change', 'Auth\RestoreController@change');
    });
});

Route::middleware('api')->prefix('email')->group(function () {
    Route::post('/verify/resend', 'Auth\VerificationController@resend')->middleware(['auth:sanctum', 'throttle:1,1'])->name('email.resend');
    Route::post('/verify/pin', 'Auth\VerificationController@verify')->middleware(['auth:sanctum', 'throttle:6,1'])->name('email.verify');
});

Route::middleware('api')->group(function () {
    Route::prefix('users')->group(function () {
        Route::middleware(['auth:sanctum', 'verified'])->post('/me', 'User\MainController@me');
        Route::middleware(['auth:sanctum', 'verified'])->post('/', 'User\MainController@me');
        Route::get('/{alias}/{uuid}', 'User\MainController@getByID')->whereUuid('uuid')->where('alias', 'id|uuid');
        Route::get('/{alias}/{name}', 'User\MainController@getByName')->where('alias', 'login|name');
        Route::prefix('personal')->middleware(['auth:sanctum', 'verified'])->group(function () {
            Route::prefix('change')->group(function () {
                Route::post('/password', 'User\ChangeController@Password');
                Route::post('/email', 'User\ChangeController@Email');
                Route::post('/email/confirm', 'User\VerificationController@Email');
            });
            Route::get('/sessions', 'User\SessionController@sessions');
            Route::get('/sessions/end', 'User\SessionController@endSession');
            Route::prefix('totp')->group(function () {
                Route::get('/info', 'UserController@TotpInfo');
                Route::post('/add', 'UserController@TotpAdd');
                Route::post('/add/confirm', 'UserController@TotpAddConfirm');
                Route::post('/remove', 'UserController@TotpRemove');
                Route::post('/remove/confirm', 'UserController@TotpRemoveConfirm');
            });
        });
    });
    Route::prefix('news')->group(function () {
        Route::get('/', 'NewsController@getAll');
        Route::get('/id/{id}', 'NewsController@getByID')->whereUuid('id');
        Route::get('/like/{id}', 'NewsController@like')->whereUuid('id')->middleware(['auth:sanctum', 'verified']);
    });
    Route::prefix('changelogs')->group(function () {
        Route::get('/', 'ChangeLogs\ItemsController@getAll');
        Route::get('/id/{id}', 'NewsController@getByID')->whereUuid('id');
    });
    Route::get('/sliders/{type}', 'SlidersController@getAll');
    Route::prefix('wallets')->group(function () {
        Route::prefix("add")->middleware(['auth:sanctum', 'verified'])->group(function() {
            Route::post('/', 'WalletsController@addMe');
            Route::post('/player/{id}', 'WalletsController@addAnother')->whereUuid('id');
        });
        Route::post('/exchange', 'WalletsController@exchange')->middleware(['auth:sanctum', 'verified']);
        Route::get('/', 'WalletsController@me')->middleware(['auth:sanctum', 'verified']);
        Route::get('/me', 'WalletsController@me')->middleware(['auth:sanctum', 'verified']);
        Route::get('/history', 'WalletsController@history')->middleware(['auth:sanctum', 'verified']);
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
    Route::prefix('violations')->group(function () {
        Route::get('/', 'ViolationsController@me')->middleware(['auth:sanctum', 'verified']);
        //Route::get('/id/{id}', 'NewsController@getByID')->whereUuid('id');
    });
    // Route::prefix('others')->group(function () {
    //     Route::get('/recommendations', 'RecommendationsController');
    // });
});

Route::prefix('storage')->group(function () {
    Route::prefix('users')->group(function () {
        Route::prefix('skins')->group(function () {
            Route::get('/name/{name}', 'Storage\SkinsController@name');
            Route::get('/uuid/{uuid}', 'Storage\SkinsController@uuid');
            Route::get('/library', 'Storage\SkinsController@library');
            Route::post('/upload', 'Storage\SkinsController@upload')->middleware(['auth:sanctum', 'verified']);
            Route::post('/set', 'Storage\SkinsController@choose')->middleware(['auth:sanctum', 'verified']);
        });
        Route::prefix('cloaks')->group(function () {
            Route::get('/name/{name}', 'Storage\CloaksController@name');
            Route::get('/uuid/{uuid}', 'Storage\CloaksController@uuid');
            Route::get('/library', 'Storage\CloaksController@library');
            Route::post('/upload', 'Storage\CloaksController@upload')->middleware(['auth:sanctum', 'verified']);
        });
        Route::prefix('avatars')->group(function () {
            Route::get('/me', 'Storage\AvatarsController@me')->middleware(['auth:sanctum', 'verified']);
            Route::get('/name/{name}', 'Storage\AvatarsController@name');
            Route::get('/uuid/{uuid}', 'Storage\AvatarsController@uuid')->whereUuid('id');
        });
    });
});
Auth::routes(['verify' => true]);