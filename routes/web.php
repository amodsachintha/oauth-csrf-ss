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

Route::get('/','FacebookController@show');
Route::get('/fbcallback','FacebookController@fbcallback');
Route::get('/logged-in','FacebookController@getUserDetails');
Route::get('/post-status','FacebookController@postStatus');
Route::get('/logout','FacebookController@logout');
Route::post('/login','LocalLogin@index');


Route::get('/csrf-main',function (){
    return view('csrf');
});
Route::get('/csrf2',function (){
    return view('csrf2');
});


Route::post('/submitWithCSRF1','CSRF1@submit');
Route::post('/csrftokenendpoint','CSRF1@generateCSRFToken');



Route::post('/submitWithCSRF2','CSRF2@submit');