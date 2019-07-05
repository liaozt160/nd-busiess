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
    $schema = $request->server('HTTP_X_FORWARDED_PROTO')?'https':'http';
    $url = $schema.'://'.$request->getHost().'/web';
    return redirect($url);
});

Route::get('/info', function () {
//    echo phpinfo();
});

Route::get('/test', function () {

});

Route::get('/vue', function () {
    return view('vue');
});