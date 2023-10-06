<?php

use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// User auth
Auth::routes();
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
// Admin user auth
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->middleware('guest:admin_user')->name('get.admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->middleware('guest:admin_user')->name('post.admin.login');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->middleware('auth:admin_user')->name('admin.logout');

Route::group([
    'namespace' => 'App\Http\Controllers\Frontend',
    'middleware' => ['auth']
], function () {
    Route::get('/', 'HomeController@index')->name('home');
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::get('/update-password', 'PasswordController@index')->name('get-update-password');
    Route::post('/update-password', 'PasswordController@update')->name('post-update-password');
    Route::get('/wallet', 'WalletController@index')->name('get-wallet-index');
    Route::get('/check_account', 'WalletController@check')->name('get-wallet-check-account');
    Route::get('/transfer', 'WalletController@transfer')->name('get-wallet-transfer');
    Route::get('/transfer/confirm', 'WalletController@transferConfirm')->name('post-wallet-transfer-confirm');
    Route::post('/transfer/complete', 'WalletController@transferComplete')->name('post-wallet-transfer-complete');
    Route::get('/transfer/confirm/password/check', 'WalletController@passwordCheck')->name('get-tranfer-password-check');
});