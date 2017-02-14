<?php
Route::group(['prefix' => '/v1'], function () {

//    Route::get('/category','insertSectionController@categoryOrder');
//    Route::get('/division','insertSectionController@divisionActive');
//    Route::get('/insert','insertSectionController@check');

//    Route::get('test', 'BackupController@DatabaseBackup'); // get detail example


    Route::group(['prefix' => '/haitao/'], function () {
        Route::group(['prefix' => 'product/'], function () {
            Route::get('{haitao_product_id}', 'HaitaoController@productDetailGet'); // get detail example
        });
        Route::group(['prefix' => 'order/'], function () {
            Route::post('', 'HaitaoController@orderStore');
            Route::put('{haitao_order_id}', 'HaitaoController@orderPut');
        });
        Route::group(['prefix' => 'review/'], function () {
            Route::get('product/{haitao_product_id}', 'ReviewController@getListByHaitaoProductId');
            Route::get('user/{haitao_user_id}', 'ReviewController@getListByHaitaoUserId');
        });

    });
    Route::group(['prefix' => '/members/'], function () {
        //authenicates
        Route::post('signin', 'Auth\AuthController@signin');
        Route::put('signout', 'Auth\AuthController@signout');
        Route::post('signup', 'Auth\AuthController@signup');
        Route::delete('signdrop', 'Auth\AuthController@signdrop');

        //member data check and get
        Route::get('', 'Auth\AuthController@getList');
        Route::get('simple', 'Auth\AuthController@simpleRetrieve');
        Route::get('detail/{id}', 'Auth\AuthController@getRetrieve');
        Route::post('detail/{id}', 'Auth\AuthController@postRetrieve');

        //about password
        Route::group(['prefix' => 'pwd/'], function () {
            Route::post('reset', 'Auth\PasswordController@postReset');
        });
    });
    Route::group(['prefix' => '/products/'], function () {
        Route::get('', 'ProductController@getList');
        Route::get('simple/{id}', 'ProductController@getSimple');
        Route::get('detail/{id}', 'ProductController@get');
        Route::post('', 'ProductController@post');
        Route::put('detail/{id}', 'ProductController@put');
        Route::delete('detail/{id}', 'ProductController@delete');

        Route::put('status/{status_name}', 'ProductController@status');

        Route::group(['prefix' => 'freegift/'], function () {
            Route::get('{product_id}', 'FreeGiftController@getList');
            Route::post('{product_id}', 'FreeGiftController@post');
            Route::group(['prefix' => 'winner/'], function () {
                Route::post('{user_id}', 'FreeGiftController@winnerPost');
            });
        });
    });
    Route::group(['prefix' => '/orders/'], function () {
        Route::get('', 'OrderController@getList');
        Route::put('{order_id}', 'OrderController@put');
    });
    Route::group(['prefix' => '/markets/'], function () {
        Route::get('product', 'MarketController@get');
        Route::put('product/stock/{product_id}', 'MarketController@updateStock');
        Route::put('product/schedule', 'MarketController@updateScheduling');
    });
    Route::group(['prefix' => '/categories/'], function () {
        Route::get('', 'CategoryController@getList');
        Route::post('', 'CategoryController@post');
        Route::put('{id}', 'CategoryController@put');
        Route::delete('{id}', 'CategoryController@delete');
    });
    Route::group(['prefix' => '/divisions/'], function () {
        Route::get('', 'DivisionController@getList');
        Route::post('', 'DivisionController@post');
        Route::put('{id}', 'DivisionController@put');
        Route::delete('{id}', 'DivisionController@delete');
    });
    Route::group(['prefix' => '/sections/'], function () {
        Route::get('', 'SectionController@getList');
        Route::post('', 'SectionController@post');
        Route::put('{id}', 'SectionController@put');
        Route::delete('{id}', 'SectionController@delete');
    });
    Route::group(['prefix' => '/surveys/'], function () {
        Route::get('detail/{user_id}', 'SurveyController@get');
        Route::get('', 'SurveyController@getList');
        Route::post('', 'SurveyController@post');
    });
    Route::group(['prefix' => '/reviews/'], function () {
        Route::get('detail/{review_id}', 'ReviewController@get');
        Route::get('', 'ReviewController@getList');
        Route::post('{target_id}', 'ReviewController@post');
        Route::put('detail/{review_id}', 'ReviewController@put');
    });
    Route::group(['prefix' => '/questions/'], function () {
        Route::get('list/{target}/{target_id}', 'QuestionController@get');
        Route::group(['prefix' => 'key/'], function () {
            Route::get('{division_id}', 'QuestionController@getKeys');
            Route::post('', 'QuestionController@postKey');
        });
    });
    Route::group(['prefix' => '/give/'], function () {
        Route::group(['prefix' => 'apply/'], function () {
            Route::get('{user_id}', 'GiveApplyController@getList');
            Route::post('{review_id}', 'GiveApplyController@post');
        });
        Route::group(['prefix' => 'accept/'], function () {
            Route::get('{review_id}', 'GiveAcceptController@getList');
            Route::post('{review_id}', 'GiveAcceptController@post');
        });

    });



    Route::get('/data/','DataResponseController@dataSimpleResponse');
});
