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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('app-version', 'Api\V1\CommonController@appVersion');
Route::group(['namespace' => 'Api\V1','prefix' => 'v1'], function () {
    Route::get('categories', 'CommonController@categories');
    
    Route::post('login', 'LoginController@login');
    Route::post('register', 'LoginController@register');
    Route::post('forgot-password', 'LoginController@forgotPassword');

    Route::group(['middleware' => 'userAuth'], function() {
		Route::get('logout', 'UserController@logout');
		Route::post('passwordReset', 'UserController@changePassword');
		Route::get('profile', 'UserController@index');
		Route::post('profile/update', 'UserController@profileUpdate');
		Route::get('search-friends', 'UserController@searchFriends');
		
		Route::get('my-friends', 'UserController@myFriends');
		Route::get('friend-request-list', 'UserController@friendRequestList');
		Route::post('friend-request', 'UserController@friendRequest'); // send Or Cancel
		Route::post('friend-request-confirm', 'UserController@friendRequestConfirm'); // Accept Or Cancel
		
		Route::post('post-create', 'PostController@create'); // Accept Or Cancel
	});
});