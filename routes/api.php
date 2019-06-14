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
            Route::post('account/status', 'AccountController@accountStatus');
            Route::post('account/password', 'AccountController@accountPassword');

            Route::post('account/assign', 'AccountController@businessAssign');
            Route::post('account/assign/list', 'AccountController@businessAssignList');
            Route::post('account/attention/list', 'AccountController@businessAttentionList');
//            Route::post('business/assign', 'AccountController@accountStatus');
        });

        Route::middleware(['access.business'])->group(function (){
            Route::post('business/add', 'BusinessController@Add');
            Route::post('business/update', 'BusinessController@Update');
            Route::post('business/del', 'BusinessController@Delete');
            Route::post('business/list', 'BusinessController@List');
            Route::post('business/show', 'BusinessController@Show');
        });

        Route::middleware(['access.buyer'])->group(function (){
            Route::post('buyer/add', 'BuyerController@Add');
            Route::post('buyer/update', 'BuyerController@Update');
            Route::post('buyer/del', 'BuyerController@Delete');
            Route::post('buyer/list', 'BuyerController@List');
            Route::post('buyer/show', 'BuyerController@Show');
            Route::post('buyer/attention/pay', 'BuyerController@attentionPay');
            Route::post('buyer/attention/list', 'BuyerController@attentionList');
            Route::post('buyer/attention/del', 'BuyerController@attentionDel');
        });

    });

});