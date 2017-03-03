<?php
Route::group(['prefix' => '/v1'], function () {
    Route::group(['prefix' => '/members/'], function () {
        Route::post('signin', 'Auth\AuthController@signin');
        Route::put('signout', 'Auth\AuthController@signout');
        Route::post('signup', 'Auth\AuthController@signup');
        Route::delete('signdrop', 'Auth\AuthController@signdrop');

//        Route::get('', 'Auth\AuthController@getList');

        Route::get('simple', 'Auth\AuthController@simpleRetrieve');
        Route::get('detail/{id}', 'Auth\AuthController@getRetrieve');
        Route::post('detail/{id}', 'Auth\AuthController@postRetrieve');

        Route::group(['prefix' => 'pwd/'], function () {
            Route::post('reset', 'Auth\PasswordController@postReset');
        });
    });
    Route::group(['prefix' => '/products/'], function () {
        Route::get('', 'ProductController@getList');
        Route::get('simple/{id}', 'ProductController@getSimple');
        Route::get('detail/{id}', 'ProductController@get');

//        Route::group(['prefix' => 'freegift/'], function () {
//            Route::get('{product_id}', 'FreeGiftController@get');
//        });
    });
    Route::group(['prefix' => '/categories/'], function () {
        Route::get('', 'CategoryController@getList');
    });
    Route::group(['prefix' => '/divisions/'], function () {
        Route::get('', 'DivisionController@getList');
    });
    Route::group(['prefix' => '/sections/'], function () {
        Route::get('', 'SectionController@getList');
    });
//    Route::group(['prefix' => '/surveys/'], function () {
//        Route::get('detail/{user_id}', 'SurveyController@get');
//        Route::get('', 'SurveyController@getList');
//        Route::post('', 'SurveyController@post');
//    });
//    Route::group(['prefix' => '/reviews/'], function () {
//        Route::get('detail/{review_id}', 'ReviewController@get');
//        Route::get('', 'ReviewController@getList');
//        Route::post('{award_id}', 'ReviewController@post');
//        Route::put('detail/{review_id}', 'ReviewController@put');
//    });
//    Route::group(['prefix' => '/questions/'], function () {
//        Route::get('list/{target}/{target_id}', 'QuestionController@get');
//        Route::group(['prefix' => 'key/'], function () {
//            Route::get('{division_id}', 'QuestionController@getKeys');
//            Route::post('', 'QuestionController@postKey');
//        });
//    });
//    Route::group(['prefix' => '/awards/'], function () {
//        Route::get('{user_id}', 'AwardController@getListByUserId');
//    });
//    Route::group(['prefix' => '/give/'], function () {
//        Route::group(['prefix' => 'apply/'], function () {
//            Route::get('{user_id}', 'GiveApplyController@getList');
//            Route::post('{review_id}', 'GiveApplyController@post');
//        });
//        Route::group(['prefix' => 'accept/'], function () {
//            Route::get('{review_id}', 'GiveAcceptController@getList');
//            Route::post('{review_id}', 'GiveAcceptController@post');
//        });
//    });

    Route::group(['prefix' => '/paypal'], function () {
        Route::group(['prefix' => '/payments'], function () {
            Route::get('detail', 'PaypalPaymentController@detail');
            Route::post('create', 'PaypalPaymentController@payment');
            Route::post('execute', 'PaypalPaymentController@execute');
            Route::put('expire', 'PaypalPaymentController@expire');
        });
    });
    Route::group(['prefix' => '/currency'], function () {
        Route::get('', 'CurrencyController@get');
    });
    Route::group(['prefix' => '/ems'], function () {
        Route::get('', 'EmsController@get');
    });
    Route::get('/data/', 'DataResponseController@dataSimpleResponse');
});
