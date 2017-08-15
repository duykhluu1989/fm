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

        Route::get('user/generateApiKey', 'UserController@generateApiKey');

    });

    Route::group(['middleware' => ['auth', 'access', 'permission']], function() {

        Route::get('order', 'OrderController@adminOrder');

        Route::match(['get', 'post'], 'order/{id}/detail', 'OrderController@detailOrder');

        Route::get('widget', 'WidgetController@adminWidget');

        Route::match(['get', 'post'], 'widget/{id}/edit', 'WidgetController@editWidget');

        Route::get('elFinder/popup', 'ElFinderController@popup');

        Route::match(['get', 'post'], 'elFinder/popupConnector', 'ElFinderController@popupConnector');

        Route::get('elFinder/tinymce', 'ElFinderController@tinymce');

        Route::get('article', 'ArticleController@adminArticle');

        Route::match(['get', 'post'], 'article/create', 'ArticleController@createArticle');

        Route::match(['get', 'post'], 'article/{id}/edit', 'ArticleController@editArticle');

        Route::get('article/{id}/delete', 'ArticleController@deleteArticle');

        Route::get('article/controlDelete', 'ArticleController@controlDeleteArticle');

        Route::get('user', 'UserController@adminUser');

        Route::get('userCustomer', 'UserController@adminUserCustomer');

        Route::match(['get', 'post'], 'user/create', 'UserController@createUser');

        Route::match(['get', 'post'], 'user/{id}/edit', 'UserController@editUser');

        Route::get('role', 'RoleController@adminRole');

        Route::match(['get', 'post'], 'role/create', 'RoleController@createRole');

        Route::match(['get', 'post'], 'role/{id}/edit', 'RoleController@editRole');

        Route::get('role/{id}/delete', 'RoleController@deleteRole');

        Route::get('role/controlDelete', 'RoleController@controlDeleteRole');

        Route::match(['get', 'post'], 'setting', 'SettingController@adminSetting');

        Route::match(['get', 'post'], 'setting/social', 'SettingController@adminSettingSocial');

    });

});
