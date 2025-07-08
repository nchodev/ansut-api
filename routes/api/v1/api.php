<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPmail;
Route::group(['namespace'=>'Api\V1'], function(){


Route::get('/test-email', function () {
    // Mail::raw('Ceci est un test depuis Office365.', function ($message) {
    //     $message->to('jeanfidele19@gmail.com')
    //             ->subject('Test Email via Office365');
    // });
   Mail::to('jeanfidele19@gmail.com')->send(new OTPmail($otp ?? '123456'));


    return 'Email envoyé !';
});

Route::post('/send-otp', 'Auth\AuthController@send');

    Route::group(['prefix'=>'auth','namespace'=>'Auth'], function(){
            Route::post('/register','AuthController@register');
            Route::post('register-with-oauth/','AuthController@registerWithOAuth');
            Route::post('send-register-otp','AuthController@sendRegisterOTP');
             Route::post('verify-otp', 'AuthController@verifyOtp');
            Route::get('/cities','AuthController@getCities');
            Route::get('/schools/{id}','AuthController@getSchoolById');
            Route::get('/grades/{id}','AuthController@getSchoolGrades');
            Route::get('/social-statut','AuthController@getSocialStatut');
            Route::get('/mother-tongues','AuthController@getMotherTongues');
            
            Route::post('/login','AuthController@login');
            Route::post('/update-password','AuthController@updatePassword');
            Route::post('/logout','AuthController@logout')->middleware('auth:sanctum');
    });
   

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::put('cm-firebase-token', 'Auth\AuthController@update_cm_firebase_token');
        Route::post('send-social-register-otp', 'Auth\AuthController@sendSocialRegisterOTP');
        Route::post('verify-otp', 'Auth\AuthController@verifyOtp');
        Route::post('social-register/','UserController@register');
        Route::get('/user-info','UserController@getUserinfo');
        Route::Post('/update-avatar','UserController@updateAvatar');
        

        Route::group(['prefix'=>'periods'], function(){
                Route::get('get-symptoms', 'PeriodController@getSymptoms');
                Route::post('store-periods', 'PeriodController@store');
                Route::get('get-periods', 'PeriodController@index');
                Route::get('predictions', 'PeriodController@predictCycles');
                
        });
    
    });

});
