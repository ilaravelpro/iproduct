<?php
/*
 * Author: Amirhossein Jahani | iAmir.net
 * Email: me@iamir.net
 * Mobile No: +98-9146941147
 * Last modified: 2021/08/29 Sun 04:42 PM IRDT
 * Copyright (c) 2020-2022. Powered by iAmir.net
 */

Route::namespace('v1')->prefix('v1')->middleware('authIf:api')->group(function () {
    Route::apiResource('products', 'ProductController', ['as' => 'api']);
    Route::post('products/{record}/favorite', 'ProductController@favorite')->name('api.products.favorite');
    Route::post('products/{record}/send_alert', 'ProductController@send_alert')->name('api.products.send_alert');
    Route::apiResource('prices', 'PriceController', ['as' => 'api']);
    Route::apiResource('product_alerts', 'ProductAlertController', ['as' => 'api']);
    Route::apiResource('product_collections', 'ProductCollectionController', ['as' => 'api']);
});
