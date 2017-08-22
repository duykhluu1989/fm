<?php

Route::group(['namespace' => 'Api'], function() {

    Route::group(['prefix' => 'delivery'], function() {

        Route::post('create', 'DeliveryController@addDelivery');

        Route::post('view', 'DeliveryController@viewDelivery');

        Route::post('update', 'DeliveryController@editDelivery');

        Route::post('delete', 'DeliveryController@deleteDelivery');

    });

});
