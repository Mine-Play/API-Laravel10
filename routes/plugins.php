<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Controller;

Route::prefix("luckperms")->group(function() {
    Route::post('create', 'ChangeLogsController@create');
});