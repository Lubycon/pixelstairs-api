<?php
Route::group(['prefix' => '/v1'], function () {


    Route::controller('/test', 'ActivityController');


    //about members event
    Route::group(['prefix' => '/members/'], function () {
        //authenicates
        Route::post('signin', 'Auth\AuthController@signin');
        Route::put('signout', 'Auth\AuthController@signout');
        Route::post('signup', 'Auth\AuthController@signup');
        Route::delete('signdrop', 'Auth\AuthController@signdrop');
        Route::put('signrestore/{id}', 'Auth\AuthController@signrestore');

        //member data check and get
        Route::post('isexist' , 'Auth\AuthController@checkMemberExist');
        Route::get('simple', 'Auth\AuthController@simpleRetrieve');
        Route::get('detail/{id}', 'Auth\AuthController@getRetrieve');
        Route::post('detail/{id}', 'Auth\AuthController@postRetrieve');

        //about password
        Route::group(['prefix' => 'pwd/'], function () {
            Route::post('mail', 'Auth\PasswordController@postEmail');
            Route::post('reset', 'Auth\PasswordController@postReset');
        });
    });

    //certificate receive data
    Route::group(['prefix' => '/certs/'], function () {
        Route::post('token', 'CertificateController@certToken');
        Route::post('pwd', 'CertificateController@certPassword');

        Route::group(['prefix' => 'signup/'], function () {
            Route::get('time', 'CertificateController@certTokenTimeCheck');
            Route::post('code', 'CertificateController@certSignupToken');
        });

        Route::group(['prefix' => 'password/'], function () {
            Route::post('time', 'CertificateController@certPasswordTimeCheck');
            Route::post('code', 'CertificateController@certPasswordToken');
        });
    });

    //just send mail
    Route::group(['prefix' => '/mail/'], function () {
        Route::put('signup','Auth\AuthController@signupTokenReminder');
        Route::put('pwd','Auth\PasswordController@postEmail');
    });

    //provide databases data
    Route::get('/data/','DataResponseController@dataSimpleResponse');

    //post
    Route::group(['prefix' => '/posts/'],function(){
        Route::get('{category}','BoardController@listPost');
        Route::get('{category}/{board_id}','BoardController@viewPost');
        Route::post('{category}','BoardController@uploadPost');
        Route::put('{category}/{board_id}','BoardController@updatePost');
        Route::delete('{category}/{board_id}','BoardController@deletePost');
    });

    Route::group(['prefix' => '/comments/'],function(){
        Route::post('/{category}/{board_id}','CommentController@store');
        Route::get('/{category}/{board_id?}','CommentController@getList');
        Route::put('/{category}/{board_id}/{comment_id}','CommentController@update');
        Route::delete('/{category}/{board_id}/{comment_id}','CommentController@delete');
    });

    Route::group(['prefix' => '/contents/'],function(){
        Route::get('{category}','ContentController@getList');
        Route::get('{category}/{board_id}','ContentController@viewPost');
        Route::get('{category}/{board_id}/data','ContentController@viewData');
        Route::post('{category}','ContentController@store');
        Route::put('{category}/{board_id}','ContentController@update');
        Route::delete('{category}/{board_id}','ContentController@delete');
    });

    Route::post('/tracker','TrackerController@create');
});
