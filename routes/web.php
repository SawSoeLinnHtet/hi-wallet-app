<?php

use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\PageController;

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
// Admin user auth
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->middleware('guest:admin_user')->name('get.admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->middleware('guest:admin_user')->name('post.admin.login');
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->middleware('auth:admin_user')->name('admin.logout');

Route::get('/', [PageController::class, 'home'])->name('home');