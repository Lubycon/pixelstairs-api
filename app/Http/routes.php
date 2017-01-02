<?php
Route::group(['prefix' => '/v1'], function () {


    //about members event
    Route::group(['prefix' => '/members/'], function () {
        //authenicates
        Route::post('signin', 'Auth\AuthController@signin');
        Route::put('signout', 'Auth\AuthController@signout');
        Route::post('signup', 'Auth\AuthController@signup');
        Route::delete('signdrop', 'Auth\AuthController@signdrop');

        //member data check and get
        Route::get('', 'Auth\AuthController@getList');
        Route::get('detail/{id}', 'Auth\AuthController@getRetrieve');
        Route::post('detail/{id}', 'Auth\AuthController@postRetrieve');

        //about password
        Route::group(['prefix' => 'pwd/'], function () {
            Route::post('reset', 'Auth\PasswordController@postReset');
        });
    });
});
