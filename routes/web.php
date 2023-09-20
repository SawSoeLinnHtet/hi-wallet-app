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
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('get_admin_login');
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('post_admin_login');

Route::get('/', [PageController::class, 'home'])->name('home');