<?php

Route::group(['namespace' => 'Frontend', 'middleware' => ['locale']], function() {

    Route::group(['middleware' => 'guest'], function() {

        Route::get('login', 'UserController@login');

        Route::post('login', ['middleware' => 'throttle:5,30', 'uses' => 'UserController@login']);

        Route::post('register', ['middleware' => 'throttle:5,30', 'uses' => 'UserController@register']);

    });

    Route::group(['middleware' => ['auth', 'access']], function() {

        Route::get('logout', 'UserController@logout');

    });

    Route::get('district', 'OrderController@getListDistrict');

    Route::get('/', 'HomeController@home');

    Route::post('refreshCsrfToken', 'HomeController@refreshCsrfToken');

});