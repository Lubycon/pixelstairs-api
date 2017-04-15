<?php
Route::group(['prefix' => '/v1'], function () {
    Route::group(['prefix' => '/members/'], function () {
        Route::post('signin', 'Auth\AuthController@signin');
        Route::put('signout', 'Auth\AuthController@signout');
        Route::post('signup', 'Auth\AuthController@signup');
        Route::delete('signdrop', 'Auth\AuthController@signdrop');

        Route::get('simple', 'Member\MemberController@simpleRetrieve');
        Route::group(['prefix' => '{id}/'], function () {
            Route::get('detail', 'Member\MemberController@getRetrieve');
            Route::post('detail', 'Member\MemberController@postRetrieve');
        });
        Route::group(['prefix' => 'password/'], function () {
            Route::post('mail', 'Auth\PasswordController@postMail');
            Route::put('reset', 'Auth\PasswordController@reset');
        });
    });
    Route::group(['prefix' => '/certs/'], function () {
        Route::group(['prefix' => '/signup/'], function () {
            Route::post('code', 'Cert\CertificationController@checkCode');
            Route::post('time', 'Cert\CertificationController@getDiffTime');
        });
        Route::group(['prefix' => '/password/'], function () {
            Route::post('code', 'Auth\PasswordController@checkCode');
            Route::post('time', 'Auth\PasswordController@getDiffTime');
            Route::post('', 'Auth\PasswordController@checkPassword');
        });
        Route::group(['prefix' => '/token/'], function () {
            Route::post('', 'Cert\CertificationController@checkAccessToken');
        });
    });
    Route::group(['prefix' => '/mail/'], function () {
        Route::put('signup', 'Mail\MailSendController@resendSignup');
        Route::put('password', 'Auth\PasswordController@postMail');
    });

    Route::group(['prefix' => '/contents/'], function () {
        Route::get('', 'Content\ContentController@getList');
        Route::post('', 'Content\ContentController@post');

        Route::group(['prefix' => '{content_id}/'], function () {
            Route::get('', 'Content\ContentController@get');
            Route::put('', 'Content\ContentController@put');
            Route::delete('', 'Content\ContentController@delete');

            Route::group(['prefix' => 'like/'], function () {
                Route::post('', 'Interest\InterestController@postLike');
                Route::delete('', 'Interest\InterestController@deleteLike');
            });
            Route::group(['prefix' => 'comments/'], function () {
                Route::get('', 'Comment\CommentController@getList');
                Route::post('', 'Comment\CommentController@post');
                Route::put('', 'Comment\CommentController@put');
                Route::delete('', 'Comment\CommentController@delete');
            });

        });
    });
    Route::get('/data/', 'Data\DataResponseController@dataSimpleResponse');
    Route::post('/tracker','Tracker\TrackerController@create');
});
