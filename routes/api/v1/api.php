<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['namespace'=>'Api\V1'], function(){

    Route::group(['prefix'=>'auth','namespace'=>'Auth'], function(){
            Route::post('/register','AuthController@register');
            Route::post('/login','AuthController@login');
            Route::post('/logout','AuthController@logout')->middleware('auth:sanctum');
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/user-info', function (Request $request) {
            return $request->user();
        });
    });

});
