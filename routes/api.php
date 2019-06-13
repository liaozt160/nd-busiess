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
        Route::post('/profile', 'AccountController@profile');

        Route::middleware('access.level:three')->group(function (){
//            Route::post('account/add', 'AccountController@accountAdd');
            Route::post('account/update', 'AccountController@accountUpdate');
            Route::post('account/del', 'AccountController@accountDelete');
            Route::post('account/list', 'AccountController@accountList');
            Route::post('account/show', 'AccountController@accountShow');
        });

        Route::middleware([])->group(function (){
            Route::post('business/add', 'BusinessController@Add');
            Route::post('business/update', 'BusinessController@Update');
            Route::post('business/del', 'BusinessController@Delete');
            Route::post('business/list', 'BusinessController@List');
            Route::post('business/show', 'BusinessController@Show');
        });

        Route::middleware([])->group(function (){
            Route::post('buyer/add', 'BuyerController@Add');
            Route::post('buyer/update', 'BuyerController@Update');
            Route::post('buyer/del', 'BuyerController@Delete');
            Route::post('buyer/list', 'BuyerController@List');
            Route::post('buyer/show', 'BuyerController@Show');
        });

    });

});