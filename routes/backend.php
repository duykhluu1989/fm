<?php

Route::group(['namespace' => 'Backend'], function() {

    Route::group(['middleware' => 'guest'], function() {

        Route::get('login', 'UserController@login');

        Route::post('login', ['middleware' => 'throttle:5,30', 'uses' => 'UserController@login']);

    });

    Route::group(['middleware' => ['auth', 'access']], function() {

        Route::get('logout', 'UserController@logout');

        Route::get('/', 'HomeController@home');

        Route::match(['get', 'post'], 'account/edit', 'UserController@editAccount');

        Route::post('refreshCsrfToken', 'HomeController@refreshCsrfToken');

    });

    Route::group(['middleware' => ['auth', 'access', 'permission']], function() {

        Route::get('order', 'OrderController@adminOrder');

        Route::match(['get', 'post'], 'order/{id}/detail', 'OrderController@detailOrder');

        Route::get('widget', 'WidgetController@adminWidget');

        Route::match(['get', 'post'], 'widget/{id}/edit', 'WidgetController@editWidget');

        Route::get('elFinder/popup', 'ElFinderController@popup');

        Route::match(['get', 'post'], 'elFinder/popupConnector', 'ElFinderController@popupConnector');

        Route::get('elFinder/tinymce', 'ElFinderController@tinymce');

        Route::match(['get', 'post'], 'setting', 'SettingController@adminSetting');

    });

});
