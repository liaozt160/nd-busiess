<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/login', 'NewDream\AccountController@login')->name('login');
Route::post('account/add', 'NewDream\AccountController@accountAdd');
Route::namespace('NewDream')->group(function () {
    Route::middleware('auth.token')->group(function (){
        Route::get('/logout', 'AccountController@logout');

        Route::middleware('access.level:three')->group(function (){
//            Route::post('account/add', 'AccountController@accountAdd');
            Route::post('account/update', 'AccountController@accountUpdate');
            Route::post('account/del', 'AccountController@accountDelete');
            Route::post('account/list', 'AccountController@accountList');
            Route::post('account/show', 'AccountController@accountShow');
        });

        Route::post('/profile', 'AccountController@profile');
    });

});