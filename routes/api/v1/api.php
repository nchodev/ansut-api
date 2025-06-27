<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::group(['namespace'=>'Api\V1'], function(){

    Route::group(['prefix'=>'auth','namespace'=>'Auth'], function(){
            Route::post('/register','AuthController@register');
            Route::post('register-with-oauth/','AuthController@registerWithOAuth');
            Route::get('/cities','AuthController@getCities');
            Route::get('/schools/{id}','AuthController@getSchoolById');
            Route::get('/grades/{id}','AuthController@getSchoolGrades');
            Route::get('/social-statut','AuthController@getSocialStatut');
            Route::get('/mother-tongues','AuthController@getMotherTongues');
            
            Route::post('/login','AuthController@login');
            Route::post('/logout','AuthController@logout')->middleware('auth:sanctum');
    });
   

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::put('cm-firebase-token', 'Auth\AuthController@update_cm_firebase_token');
        Route::post('send-social-register-otp', 'Auth\AuthController@sendSocialRegisterOTP');
        Route::post('verify-otp', 'Auth\AuthController@verifyOtp');
        Route::post('social-register/','UserController@register');
        
        Route::get('/user-info', function (Request $request) {
            return $request->user();
        });
    });

});
