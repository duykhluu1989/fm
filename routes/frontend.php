<?php

Route::group(['namespace' => 'Frontend', 'middleware' => ['locale']], function() {

    Route::group(['middleware' => 'guest'], function() {

        Route::get('login', 'UserController@login');

        Route::post('login', ['middleware' => 'throttle:5,30', 'uses' => 'UserController@login']);

        Route::post('register', ['middleware' => 'throttle:5,30', 'uses' => 'UserController@register']);

        Route::get('forgetPassword', 'UserController@forgetPassword');

        Route::post('forgetPassword', ['middleware' => 'throttle:5,30', 'uses' => 'UserController@forgetPassword']);

        Route::get('loginWithToken/{token}', ['middleware' => 'throttle:5,30', 'uses' => 'UserController@loginWithToken']);

        Route::get('checkRegisterEmail', 'UserController@checkRegisterEmail');

    });

    Route::group(['middleware' => ['auth', 'access']], function() {

        Route::get('userAddressForm', 'UserController@getUserAddressForm');

        Route::get('logout', 'UserController@logout');

        Route::match(['get', 'post'], 'account', 'UserController@editAccount');

        Route::get('search', 'UserController@searchOrder');

        Route::get('account/order', 'UserController@adminOrder');

        Route::get('account/order/{id}/detail', 'UserController@detailOrder');

        Route::match(['get', 'post'], 'account/order/{id}/edit', 'UserController@editOrder');

        Route::get('account/order/{id}/cancel', 'UserController@cancelOrder');

        Route::get('account/general', 'UserController@general');

    });

    Route::match(['get', 'post'], 'order', 'OrderController@placeOrder');

    Route::match(['get', 'post'], 'order/import', 'OrderController@importExcelPlaceOrder');

    Route::get('order/import/template', 'OrderController@importExcelPlaceOrderTemplate');

    Route::get('listArea', 'OrderController@getListArea');

    Route::get('orderForm', 'OrderController@getOrderForm');

    Route::get('shippingPrice', 'OrderController@calculateShippingPrice');

    Route::get('discountShippingPrice', 'OrderController@calculateDiscountShippingPrice');

    Route::get('/', 'HomeController@home');

    Route::post('refreshCsrfToken', 'HomeController@refreshCsrfToken');

    Route::get('page/{id}/{slug}', 'PageController@detailPage');

    Route::get('service', 'PageController@adminServicePage');

    Route::get('recruitment', 'PageController@adminRecruitmentPage');

    Route::match(['get', 'post'], 'mail', 'OrderController@mail');

});