<?php

use Illuminate\Http\Request;


Route::middleware('auth:api')->get('/user', function(Request $request){
    return $request->user();
});

Route::prefix('v1')->name('api.v1.')->namespace('Api\V1')->group(function(){
    Route::group(['middleware' => 'languageCheck'], function(){

        //User Api

        Route::post('signUp', 'UserController@signUp')->name('signUp');
        Route::post('login', 'UserController@login')->name('login');
        Route::post('facebookLogin', 'UserController@facebookLogin')->name('facebookLogin');
        Route::post('googleLogin', 'UserController@googleLogin')->name('googleLogin');
        Route::post('appleLogin', 'UserController@appleLogin')->name('appleLogin');

        //Route::get('passGenrate', 'PassGenrate@index')->name('passGenrate');

        Route::post('forgotPassword', 'UserController@forgotPassword')->name('forgotPassword');
        Route::post('resetPassword', 'UserController@resetPassword')->name('resetPassword');

        Route::get('applePass/{id}', 'ApplePassController@index')->name('applePass');
        Route::group(['middleware' => ['jwt.verify']], function(){
            // Route::post('changePassword', 'UserController@changePassword')->name('changePassword');
            Route::post('checkOut', 'BookingController@index')->name('checkOut');
            Route::get('refreshToken', 'UserController@refreshToken')->name('refreshToken');
            Route::post('updateProfile', 'UserController@updateProfile')->name('updateProfile');
            Route::post('getProfile', 'UserController@getProfile')->name('getProfile');
            Route::post('updateNotificationStatus', 'UserController@updateNotificationStatus')->name('updateNotificationStatus');
            Route::post('checkRideAvailable', 'VehicleController@checkRideAvailable')->name('checkRideAvailable');
            Route::post('extendContract', 'BookingController@extendContract')->name('extendContract');
            Route::get('getMyRide', 'BookingController@getMyRide')->name('getMyRide');
            Route::post('getRideDetails', 'BookingController@getRideDetails')->name('getRideDetails');
            Route::post('addRating', 'RatingController@addRating')->name('addRating');
            Route::post('rideCancel', 'BookingController@rideCancel')->name('rideCancel');
            Route::post('reportProblem', 'ReportProblemController@store')->name('reportProblem');
            Route::post('contactUs', 'ContactUsController@store')->name('contactUs');

            //Dealer Api
            Route::get('getBranch', 'BranchController@getBranch')->name('getBranch');
            Route::get('dealerGetProfile', 'DealerController@dealerGetProfile')->name('dealerGetProfile');
            Route::post('dealerGetBooking', 'DealerBookingController@dealerGetBooking')->name('dealerGetBooking');
            Route::post('addInspection', 'DealerBookingController@addInspection')->name('addInspection');


        });

        Route::post('featuredCategories', 'CategoryController@index')->name('featuredCategories');
        Route::post('featuredCategoriesDetails', 'VehicleController@featuredCategoriesDetails')->name('featuredCategoriesDetails');

        Route::get('page/{id}', 'PageController@index')->name('page');
        Route::post('search/{page?}', 'VehicleController@index')->name('search');
        Route::get('vehicleDetails/{id}', 'VehicleController@show')->name('vehicleDetails');


        Route::get('languages', 'LanguageController@index')->name('languages');
        Route::get('languageString/{locale}', 'LanguageStringController@index')->name('languageString');
        Route::get('countries', 'CountryController@index')->name('countries');


    });
});
