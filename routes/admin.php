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
	    Route::get('user-delete','DashboardController@userDelete')->name('userDelete');
	    Route::get('user-status','DashboardController@userStatusChange')->name('userStatusChange');

	    Route::get('categories','CategoryController@index')->name('categories');
	    Route::post('category/create','CategoryController@create')->name('category.create');
	    Route::get('category/edit/{id}','CategoryController@create')->name('category.edit');
	    Route::post('category/edit/{id}','CategoryController@update')->name('category.update');
	    Route::get('category-delete','CategoryController@delete')->name('category.delete');
	    Route::get('category-status','CategoryController@status')->name('category.statusChange');
	    
	});
});