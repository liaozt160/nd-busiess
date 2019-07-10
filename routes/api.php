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
        Route::post('/profile/password', 'AccountController@profilePassword');
        Route::post('/profile/test', 'AccountController@test');

        Route::post('/dashboard/index', 'AccountController@dashboard');

        Route::post('/location/query', 'LocationController@query');
        Route::post('/business/query', 'BusinessController@query');
        Route::post('/business/attention/query', 'BusinessController@queryByAttention');

        Route::middleware('access.level:three')->group(function (){
//            Route::post('account/add', 'AccountController@accountAdd'); //remark
            Route::post('account/update', 'AccountController@accountUpdate');
            Route::post('account/del', 'AccountController@accountDelete');
            Route::post('account/list', 'AccountController@accountList');
            Route::post('account/show', 'AccountController@accountShow');
            Route::post('account/status', 'AccountController@accountStatus');
            Route::post('account/password', 'AccountController@accountPassword');

            Route::post('account/assign', 'AccountController@businessAssign');
            Route::post('account/assign/list', 'AccountController@businessAssignListTo');
            Route::post('account/attention/list', 'AccountController@businessAttentionList');

            Route::post('account/buyer/services/pay', 'BuyerController@servicePay');
            Route::post('account/buyer/assign/get', 'AccountController@businessAttentionList');
            Route::post('account/buyer/assign/update', 'AccountController@businessAttentionList');

            Route::post('order/audit', 'OrderController@Audit');

            //broker net
            Route::post('account/business/net/add', 'BusinessBrokerNetController@Add');
            Route::post('account/business/net/list', 'BusinessBrokerNetController@List');
            Route::post('account/business/net/del', 'BusinessBrokerNetController@Delete');
            Route::post('account/business/net/update', 'BusinessBrokerNetController@Update');
            Route::post('account/business/net/members', 'BusinessBrokerNetController@getMember');
            Route::post('account/business/net/manager', 'BusinessBrokerNetController@setManager');
            Route::post('account/business/net/brokers', 'BusinessBrokerNetController@brokers');
            Route::post('account/business/net/show', 'BusinessBrokerNetController@Show');

            // business
            Route::post('account/business/owner', 'BusinessController@changeOwner');
        });

        Route::middleware(['access.business'])->group(function (){
            Route::post('business/add', 'BusinessController@Add');
            Route::post('business/update', 'BusinessController@Update');
            Route::post('business/show', 'BusinessController@Show');

//            Route::post('business/zh/add', 'BusinessController@AddZh');
            Route::post('business/zh/update', 'BusinessController@UpdateZh');
            Route::post('business/zh/show', 'BusinessController@ShowZh');

            Route::post('business/del', 'BusinessController@Delete');
            Route::post('business/list', 'BusinessController@List');
            Route::post('business/status', 'BusinessController@setStatus');

            Route::post('business/attention/list', 'BusinessController@attentionList');
            Route::post('business/attention/del', 'BusinessController@attentionDel');

            Route::post('business/brokers', 'BusinessController@getBusinessBrokers');


        });

        Route::middleware(['access.buyer'])->group(function (){
            Route::post('buyer/add', 'BuyerController@Add');
            Route::post('buyer/update', 'BuyerController@Update');
            Route::post('buyer/del', 'BuyerController@Delete');
            Route::post('buyer/list', 'BuyerController@List');
            Route::post('buyer/show', 'BuyerController@Show');
            Route::post('buyer/query', 'BuyerController@Query');
            Route::post('buyer/attention/pay', 'BuyerController@attentionPay');
            Route::post('buyer/attention/list', 'BuyerController@attentionList');
            Route::post('buyer/attention/del', 'BuyerController@attentionDel');

            Route::post('business/buyer/list/level/one', 'BusinessController@BuyerListLevelOne');
            Route::post('business/buyer/list/level/two', 'BusinessController@BuyerListLevelTwo');
            Route::post('business/buyer/show/level/one', 'BusinessController@showLevelOne');
            Route::post('business/buyer/show/level/two', 'BusinessController@showLevelTwo');

            Route::post('order/list', 'OrderController@list');
            Route::post('order/add', 'OrderController@Add');
            Route::post('order/update', 'OrderController@Update');
            Route::post('order/del', 'OrderController@Delete');
            Route::post('order/show', 'OrderController@Show');
            Route::post('order/view', 'OrderController@View');
            Route::post('order/status', 'OrderController@Status');
        });
    });

});