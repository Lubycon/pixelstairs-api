<?php
Route::group(['prefix' => '/v1'], function () {
//    Route::get('/category','insertSectorController@categoryOrder');
//    Route::get('/division','insertSectorController@divisionActive');
//    Route::get('/insert','insertSectorController@check');
    Route::group(['prefix' => '/haitao/'], function () {
        Route::group(['prefix' => 'product/'], function () {
            Route::get('', 'ProductController@haitaoData');
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
        Route::post('', 'ProductController@post');
        Route::get('detail/{id}', 'ProductController@get');
        Route::put('detail/{id}', 'ProductController@put');
        Route::get('', 'ProductController@getList');
    });
    Route::group(['prefix' => '/markets/'], function () {
        Route::get('product', 'MarketController@get');
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
    Route::get('/data/','DataResponseController@dataSimpleResponse');
});
