<?php
Route::get('/', function () {
    return view('welcome', ['ifYouWantPassData' => 'put in here', 'and' => 'more']);
});

Route::group(['prefix' => '/v1'], function () {
    /* FOR TEST START */
    Route::group(['prefix' => '/test'], function () {
        Route::post('testerReset', 'Auth\AuthController@testerReset');

        Route::group(['prefix' => '/mail'], function () {
            Route::post('signup', 'Auth\AuthController@signupTest');
            Route::post('password', 'Auth\PasswordController@postMailTest');
        });
    });
    /* FOR TEST API END */
    Route::group(['prefix' => '/members/'], function () {
        Route::post('signin', 'Auth\AuthController@signin');
        Route::put('signout', 'Auth\AuthController@signout');
        Route::post('signup', 'Auth\AuthController@signup');
        Route::delete('signdrop', 'Auth\AuthController@signdrop');
        Route::post('isexist', 'Auth\AuthController@isExist');

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
                Route::post('', 'Content\InterestController@postLike');
                Route::delete('', 'Content\InterestController@deleteLike');
            });
            Route::group(['prefix' => 'comments/'], function () {
                Route::get('', 'Comment\CommentController@getList');
                Route::post('', 'Comment\CommentController@post');
                Route::put('{comment_id}', 'Comment\CommentController@put');
                Route::delete('{comment_id}', 'Comment\CommentController@delete');
            });

        });
    });

    Route::group(['prefix' => '/quotes/'], function () {
        Route::get('{category}', 'Quote\QuoteController@get');
    });
//    Route::get('/data', 'Data\DataResponseController@dataSimpleResponse');
    Route::post('/tracker','Tracker\TrackerController@create');
});
