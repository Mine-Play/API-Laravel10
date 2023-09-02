<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Controller;

Route::prefix("user")->group(function() {
    Route::get('/init/{uuid}', 'UserController@init');
    Route::get('/quit/{uuid}', 'UserController@quit');
});