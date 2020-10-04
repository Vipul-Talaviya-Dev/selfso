<?php

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

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'as' => 'admin.' ], function () {

    Route::get('login', 'LoginController@loginForm')->name('loginForm');
    Route::post('login', 'LoginController@login')->name('login');
	Route::get('logout','LoginController@logout')->name('logout');

    Route::group(['middleware' => 'adminAuth'], function () {
	    Route::get('dashboard','DashboardController@index')->name('dashboard');
	    Route::get('users','DashboardController@users')->name('users');
	});
});