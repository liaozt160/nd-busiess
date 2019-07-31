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

//Route::get('/info', function () {
//    echo phpinfo();
//});

Route::get('/pdf', function () {
    $pdf = \Illuminate\Support\Facades\App::make('dompdf.wrapper');
    $pdf->loadHTML('<style>
.page-break {
    page-break-after: always;
}
</style>
<h1>Page 1</h1>
<div class="page-break"></div>
<h1>Page 2</h1>');
    return $pdf->stream();
});

Route::get('/loadpdf', 'NewDream\OrderController@loadOrderPdf');
//Route::get('/viewpdf', function () {
//   return view();
//});


Route::get('/vue', function () {
    return view('vue');
});