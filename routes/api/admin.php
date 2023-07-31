<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Controller;

Route::prefix("changelogs")->group(function() {
    Route::post('create', 'ChangeLogsController@create');
});

Route::prefix("users")->group(function() {
    Route::prefix("violations")->group(function() {
        Route::post('ban', 'ViolationsController@ban');
        Route::post('unban', 'ViolationsController@unban');

        Route::post('mute', 'ViolationsController@mute');
        Route::post('kick', 'ViolationsController@kick');
    });
});