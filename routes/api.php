<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'namespace' => 'App\Http\Controllers\Api',
], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');

    Route::group([
        'middleware' => ['auth:api']
    ], function () {
        Route::get('profile', 'ApiPageController@profile');
        Route::post('logout', 'AuthController@logout');
        Route::get('transaction', 'ApiPageController@transaction');
        Route::get('transaction/{trx_id}', 'ApiPageController@transactionDetails');
        Route::get('notification', 'ApiPageController@notification');
        Route::get('notification/{id}', 'ApiPageController@notificationDetails');
        Route::get('check_account', 'ApiPageController@checkAccount');
        Route::get('transfer/confirm', 'ApiPageController@transferConfirm');
        Route::post('transfer/complete', 'ApiPageController@transferComplete');
        Route::get('scan_and_pay/form', 'ApiPageController@scanAndPayForm');
        Route::get('scan_and_pay/confirm', 'ApiPageController@scanAndPayConfirm');
        Route::post('scan_and_pay/complete', 'ApiPageController@scanAndPayComplete');
    });
});
