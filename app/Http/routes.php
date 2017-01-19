<?php
Route::group(['prefix' => '/v1'], function () {

//    Route::get('/category','insertSectorController@categoryOrder');
//    Route::get('/division','insertSectorController@divisionActive');
//    Route::get('/insert','insertSectorController@check');

    Route::group(['prefix' => '/haitao/'], function () {
        Route::group(['prefix' => 'product/'], function () {
            Route::get('{haitao_product_id}', 'HaitaoController@productDetailGet'); // get detail example
        });
        Route::group(['prefix' => 'order/'], function () {
            Route::post('', 'HaitaoController@orderStore');
//            Route::put('', 'HaitaoController@orderPut');
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
        Route::get('detail/{id}', 'ProductController@get');
        Route::post('', 'ProductController@post');
        Route::put('detail/{id}', 'ProductController@put');
        Route::delete('detail/{id}', 'ProductController@delete');

        Route::put('status/{status_name}', 'ProductController@status');
    });
    Route::group(['prefix' => '/markets/'], function () {
        Route::get('snoopy', 'MarketController@getBySnoopy');
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
    Route::group(['prefix' => '/sectors/'], function () {
        Route::get('', 'SectorController@getList');
        Route::post('', 'SectorController@post');
        Route::put('{id}', 'SectorController@put');
        Route::delete('{id}', 'SectorController@delete');
    });
    Route::get('/data/','DataResponseController@dataSimpleResponse');
});
