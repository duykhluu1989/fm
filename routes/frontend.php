<?php

Route::group(['namespace' => 'Frontend', 'middleware' => ['locale']], function() {

    Route::group(['middleware' => 'guest'], function() {

        Route::get('login', 'UserController@login');

        Route::post('login', ['middleware' => 'throttle:5,30', 'uses' => 'UserController@login']);

        Route::post('register', ['middleware' => 'throttle:5,30', 'uses' => 'UserController@register']);

        Route::get('quenmatkhau', 'UserController@quenmatkhau');

        Route::get('checkRegisterEmail', 'UserController@checkRegisterEmail');

    });

    Route::group(['middleware' => ['auth', 'access']], function() {

        Route::get('logout', 'UserController@logout');

        Route::match(['get', 'post'], 'account', 'UserController@editAccount');

        Route::get('account/order', 'UserController@adminOrder');

        Route::get('account/order/{id}/detail', 'UserController@detailOrder');

        Route::get('quanlydongtien', 'UserController@quanlydongtien');

        Route::get('tongquanchung', 'UserController@tongquanchung');

    });

    Route::match(['get', 'post'], 'order', 'OrderController@placeOrder');

    Route::get('area', 'OrderController@getListArea');

    Route::get('shippingPrice', 'OrderController@calculateShippingPrice');

    Route::get('/', 'HomeController@home');

    Route::post('refreshCsrfToken', 'HomeController@refreshCsrfToken');

    Route::get('gioithieu', 'PageController@gioithieu');

    Route::get('gioithieu', 'PageController@gioithieu');

    Route::get('banggia', 'PageController@banggia');

    Route::get('chinhsach', 'PageController@chinhsach');

    Route::get('dichvu', 'PageController@dichvu');

    Route::get('tuyendung', 'PageController@tuyendung');

    Route::get('tuyendungchitiet', 'PageController@tuyenchungchitiet');

});