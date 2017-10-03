<?php

if (App::environment('local')) {
    // The environment is local
    DB::enableQueryLog();
    if (env('DB_LOG', false)) {
        DB::listen(function ($query) {
            Log::info( print_r([
                'sql'      => $query->sql,
                'bindings' => $query->bindings,
                'time'     => $query->time,
            ],true));
        });
    }
}

Route::get('/', function () {
    return view('welcome', ['ifYouWantPassData' => 'put in here', 'and' => 'more']);
});

Route::group(['prefix' => '/v1', 'domain' => env('APP_URL')], function () {
    /* FOR TEST START */
    Route::group(['prefix' => '/test'], function () {

        Route::post('testerReset', 'Service\Auth\AuthController@testerReset');

        Route::group(['prefix' => '/mail'], function () {
            Route::group(['prefix' => '/signup'], function () {
                Route::post('/', 'Service\Auth\AuthController@signupTest');
                Route::post('/remind', 'Service\Mail\MailSendController@resendSignupTest');
            });
            Route::post('password', 'Service\Auth\PasswordController@postMailTest');
        });
    });
    /* FOR TEST API END */
    Route::group(['prefix' => '/members/'], function () {
        Route::post('signin', 'Service\Auth\AuthController@signin');
        Route::put('signout', 'Service\Auth\AuthController@signout');
        Route::post('signup', 'Service\Auth\AuthController@signup');
        Route::delete('signdrop', 'Service\Auth\AuthController@signdrop');
        Route::get('signdrop/survey/list', 'Service\Survey\SigndropSurveyController@getList');

        Route::group(['prefix' => 'exists/'], function () {
            Route::post('email', 'Service\Auth\AuthController@emailExist');
            Route::post('nickname', 'Service\Auth\AuthController@nicknameExist');
        });

        Route::group(['prefix' => 'me/'], function () {
            Route::get('', 'Service\Member\MemberController@getMyRetrieve');
            Route::put('', 'Service\Member\MemberController@putMyRetrieve');
        });

        Route::group(['prefix' => '{id}/'], function () {
            Route::get('', 'Service\Member\MemberController@getPublicRetrieve');
        });

        Route::group(['prefix' => 'password/'], function () {
            Route::post('token', 'Service\Auth\PasswordController@postToken');
            Route::post('mail', 'Service\Auth\PasswordController@postMail');
            Route::put('reset', 'Service\Auth\PasswordController@resetWithToken');
        });
    });
    Route::group(['prefix' => '/certs/'], function () {
        Route::group(['prefix' => '/signup/'], function () {
            Route::post('mail', 'Service\Mail\MailSendController@resendSignup');
            Route::post('code', 'Service\Cert\CertificationController@checkCode');
            Route::get('time', 'Service\Cert\CertificationController@getDiffTime');
        });
        Route::group(['prefix' => '/password/'], function () {
            Route::post('code', 'Service\Auth\PasswordController@checkCode');
            Route::post('time', 'Service\Auth\PasswordController@getDiffTime');
            Route::post('', 'Service\Auth\PasswordController@checkPassword');
        });
        Route::group(['prefix' => '/token/'], function () {
            Route::post('', 'Service\Cert\CertificationController@checkAccessToken');
        });
    });

    // !!!!!!!!!!!Deprecated
    // !!!!!!!!!!!Deprecated
    // !!!!!!!!!!!Deprecated
    // !!!!!!!!!!!Deprecated
    // !!!!!!!!!!!Deprecated
    // !!!!!!!!!!!Deprecated
    Route::group(['prefix' => '/mail/'], function () {
        Route::put('password', 'Service\Auth\PasswordController@postMail');
    });
    // !!!!!!!!!!!Deprecated
    // !!!!!!!!!!!Deprecated
    // !!!!!!!!!!!Deprecated
    // !!!!!!!!!!!Deprecated
    // !!!!!!!!!!!Deprecated
    // !!!!!!!!!!!Deprecated

    Route::group(['prefix' => '/contents/'], function () {
        Route::get('', 'Service\Content\ContentController@getList');
        Route::post('', 'Service\Content\ContentController@post');

        Route::group(['prefix' => '{content_id}/'], function () {
            Route::get('', 'Service\Content\ContentController@get');
            Route::put('', 'Service\Content\ContentController@put');
            Route::delete('', 'Service\Content\ContentController@delete');

            Route::group(['prefix' => 'image/'], function () {
                Route::post('', 'Service\Content\ContentController@uploadImage');
            });
            Route::group(['prefix' => 'like/'], function () {
                Route::post('', 'Service\Content\InterestController@postLike');
                Route::delete('', 'Service\Content\InterestController@deleteLike');
            });
            Route::group(['prefix' => 'comments/'], function () {
                Route::get('', 'Service\Comment\CommentController@getList');
                Route::post('', 'Service\Comment\CommentController@post');
                Route::put('{comment_id}', 'Service\Comment\CommentController@put');
                Route::delete('{comment_id}', 'Service\Comment\CommentController@delete');
            });

        });
    });

    Route::group(['prefix' => '/quotes/'], function () {
        Route::get('{category}', 'Service\Quote\QuoteController@get');
    });
    Route::post('/tracker', 'Service\Tracker\TrackerController@create');
    Route::get('/client', 'Service\Client\ClientController@info');
});


// ADMIN API
Route::group(['prefix' => '/', 'domain' => env('ADMIN_APP_URL')], function () {
    Route::post('/members/signin', 'Admin\Auth\AuthController@signin');
});
Route::group(['prefix' => '/', 'middleware' => 'auth.admin', 'domain' => env('ADMIN_APP_URL')], function () {
    Route::group(['prefix' => '/members/'], function () {
        Route::put('signout', 'Service\Auth\AuthController@signout');
        Route::post('signup', 'Admin\Auth\AuthController@signup');

        Route::get('', 'Admin\Member\AdminMemberController@getList');
        Route::get('simple', 'Service\Member\MemberController@simpleRetrieve');

        Route::group(['prefix' => '{id}/'], function () {
            Route::delete('signdrop', 'Admin\Auth\AuthController@signdrop');
            Route::get('detail', 'Admin\Member\AdminMemberController@getRetrieve');
            Route::put('detail', 'Admin\Member\AdminMemberController@putRetrieve');
        });

        Route::group(['prefix' => 'exists/'], function () {
            Route::post('email', 'Service\Auth\AuthController@emailExist');
            Route::post('nickname', 'Service\Auth\AuthController@nicknameExist');
        });
    });

    Route::group(['prefix' => '/blackmembers/'], function() {
        Route::get('', 'Admin\Member\AdminMemberController@getBlackUserList');
    });

    Route::group(['prefix' => '/contents/'], function () {
        Route::get('', 'Admin\Content\AdminContentController@getList');

        Route::group(['prefix' => '{content_id}/'], function () {
            Route::get('', 'Admin\Content\AdminContentController@get');
            Route::put('', 'Admin\Content\AdminContentController@put');
            Route::delete('', 'Admin\Content\AdminContentController@delete');
        });
    });
});
