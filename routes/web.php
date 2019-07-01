<?php

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

Route::get('/', function (\Illuminate\Http\Request $request) {
    echo $request->url();
    var_dump($request->getScheme());
    exit();
    return redirect($request->getScheme().$request->url().'/web');
});

Route::get('/info', function () {
    echo phpinfo();
});

Route::get('/test', function () {


});

Route::get('/vue', function () {
    return view('vue');
});