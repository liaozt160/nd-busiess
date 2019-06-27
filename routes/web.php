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
    return redirect($request->url().'/web');
});

Route::get('/info', function () {
//    echo phpinfo();
});

Route::get('/test', function () {
//    $users = \App\users::all();
//    dd($users);
});

Route::get('/vue', function () {
    return view('vue');
});