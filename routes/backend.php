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

        Route::post('article/autoComplete', 'ArticleController@autoCompleteArticle');

        Route::post('discount/generateCode', 'DiscountController@generateDiscountCode');

        Route::get('user/generateApiKey', 'UserController@generateApiKey');

        Route::post('user/autoComplete', 'UserController@autoCompleteUser');

        Route::get('listArea', 'UserController@getListArea');

        Route::get('shippingPrice', 'OrderController@calculateShippingPrice');

        Route::get('discountShippingPrice', 'OrderController@calculateDiscountShippingPrice');

        Route::get('userAddressForm', 'UserController@getUserAddressForm');

    });

    Route::group(['middleware' => ['auth', 'access', 'permission']], function() {

        Route::get('order', 'OrderController@adminOrder');

        Route::get('order/{id}/detail', 'OrderController@detailOrder');

        Route::match(['get', 'post'], 'order/{id}/edit', 'OrderController@editOrder');

        Route::get('order/{id}/payment', 'OrderController@paymentOrder');

        Route::get('order/controlPayment', 'OrderController@controlPaymentOrder');

        Route::get('order/{id}/completePayment', 'OrderController@completePaymentOrder');

        Route::post('order/upload/completePayment', 'OrderController@uploadCompletePaymentOrder');

        Route::get('order/{id}/cancel', 'OrderController@cancelOrder');

        Route::get('order/controlCancel', 'OrderController@controlCancelOrder');

        Route::get('order/controlExport', 'OrderController@controlExportOrder');

        Route::post('order/{id}/returnPrice', 'OrderController@returnPriceOrder');

        Route::get('area', 'AreaController@adminArea');

        Route::match(['get', 'post'], 'area/{id}/edit', 'AreaController@editArea');

        Route::get('area/export', 'AreaController@exportArea');

        Route::get('shippingPriceRule', 'ShippingPriceRuleController@adminShippingPriceRule');

        Route::match(['get', 'post'], 'shippingPriceRule/create', 'ShippingPriceRuleController@createShippingPriceRule');

        Route::match(['get', 'post'], 'shippingPriceRule/{id}/edit', 'ShippingPriceRuleController@editShippingPriceRule');

        Route::get('shippingPriceRule/{id}/delete', 'ShippingPriceRuleController@deleteShippingPriceRule');

        Route::get('shippingPriceRule/controlDelete', 'ShippingPriceRuleController@controlDeleteShippingPriceRule');

        Route::get('zone', 'ZoneController@adminZone');

        Route::match(['get', 'post'], 'zone/create', 'ZoneController@createZone');

        Route::match(['get', 'post'], 'zone/{id}/edit', 'ZoneController@editZone');

        Route::get('zone/{id}/delete', 'ZoneController@deleteZone');

        Route::get('zone/controlDelete', 'ZoneController@controlDeleteZone');

        Route::get('run', 'RunController@adminRun');

        Route::match(['get', 'post'], 'run/create', 'RunController@createRun');

        Route::match(['get', 'post'], 'run/{id}/edit', 'RunController@editRun');

        Route::get('run/{id}/delete', 'RunController@deleteRun');

        Route::get('run/controlDelete', 'RunController@controlDeleteRun');

        Route::get('discount', 'DiscountController@adminDiscount');

        Route::match(['get', 'post'], 'discount/create', 'DiscountController@createDiscount');

        Route::match(['get', 'post'], 'discount/{id}/edit', 'DiscountController@editDiscount');

        Route::get('discount/{id}/delete', 'DiscountController@deleteDiscount');

        Route::get('discount/controlDelete', 'DiscountController@controlDeleteDiscount');

        Route::get('widget', 'WidgetController@adminWidget');

        Route::match(['get', 'post'], 'widget/{id}/edit', 'WidgetController@editWidget');

        Route::get('elFinder/popup', 'ElFinderController@popup');

        Route::match(['get', 'post'], 'elFinder/popupConnector', 'ElFinderController@popupConnector');

        Route::get('elFinder/tinymce', 'ElFinderController@tinymce');

        Route::get('elFinder/popup/userAttachment/{id}', 'ElFinderController@popupUserAttachment');

        Route::match(['get', 'post'], 'elFinder/popupConnector/userAttachment/{id}', 'ElFinderController@popupConnectorUserAttachment');

        Route::get('article', 'ArticleController@adminArticle');

        Route::match(['get', 'post'], 'article/create', 'ArticleController@createArticle');

        Route::match(['get', 'post'], 'article/{id}/edit', 'ArticleController@editArticle');

        Route::get('article/{id}/delete', 'ArticleController@deleteArticle');

        Route::get('article/controlDelete', 'ArticleController@controlDeleteArticle');

        Route::get('user', 'UserController@adminUser');

        Route::get('userCustomer', 'UserController@adminUserCustomer');

        Route::match(['get', 'post'], 'user/create', 'UserController@createUser');

        Route::match(['get', 'post'], 'user/{id}/edit', 'UserController@editUser');

        Route::post('user/{id}/upload/placeOrder', 'UserController@importExcelPlaceOrder');

        Route::get('role', 'RoleController@adminRole');

        Route::match(['get', 'post'], 'role/create', 'RoleController@createRole');

        Route::match(['get', 'post'], 'role/{id}/edit', 'RoleController@editRole');

        Route::get('role/{id}/delete', 'RoleController@deleteRole');

        Route::get('role/controlDelete', 'RoleController@controlDeleteRole');

        Route::match(['get', 'post'], 'theme/menu', 'ThemeController@adminMenu');

        Route::post('theme/menu/create', 'ThemeController@createMenu');

        Route::match(['get', 'post'], 'theme/menu/{id}/edit', 'ThemeController@editMenu');

        Route::get('theme/menu/{id}/delete', 'ThemeController@deleteMenu');

        Route::match(['get', 'post'], 'setting', 'SettingController@adminSetting');

        Route::match(['get', 'post'], 'setting/api', 'SettingController@adminSettingApi');

        Route::match(['get', 'post'], 'setting/social', 'SettingController@adminSettingSocial');

        Route::match(['get', 'post'], 'setting/orderStatus', 'SettingController@adminSettingOrderStatus');

    });

});
