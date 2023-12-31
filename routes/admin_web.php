<?php

use App\Http\Controllers\Backend\WalletController;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\PageController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\AdminUserController;

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

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
    'middleware' => 'auth:admin_user'
], function () {
    Route::get('/', [PageController::class, 'home'])->name('home');
    Route::resource('admin-user', AdminUserController::class);
    Route::get('admin-user/datatable/ssd', [AdminUserController::class, 'ssd']);

    Route::resource('user', UserController::class);
    Route::get('user/datatable/ssd', [UserController::class, 'ssd']);

    Route::get('wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::get('wallet/datatable/ssd', [WalletController::class, 'ssd']);
    Route::get('wallet/amount/add', [WalletController::class, 'add_form'])->name('get.wallet.amount.add');
    Route::post('wallet/amount/add', [WalletController::class, 'add'])->name('post.wallet.amount.add');
    Route::get('wallet/amount/reduce', [WalletController::class, 'reduce_form'])->name('get.wallet.amount.reduce');
    Route::post('wallet/amount/reduce', [WalletController::class, 'reduce'])->name('post.wallet.amount.reduce');
});
