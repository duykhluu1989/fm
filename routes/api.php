<?php

Route::group(['namespace' => 'Api'], function() {

    Route::group(['prefix' => 'delivery'], function() {

        Route::post('create', 'DeliveryController@addDelivery');

        Route::post('view', 'DeliveryController@viewDelivery');

        Route::post('update', 'DeliveryController@editDelivery');

        Route::post('delete', 'DeliveryController@deleteDelivery');

        Route::post('notification', 'DeliveryController@handleDeliveryNotification');

    });

    Route::group(['prefix' => 'collection'], function() {

        Route::post('notification', 'CollectionController@handleCollectionNotification');

    });

});
