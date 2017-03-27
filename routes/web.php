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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', 'IndexController@login');
Route::get('/reg_client', 'IndexController@reg_client');
Route::get('/open_bid', 'IndexController@open_bid');
Route::get('/close_bid', 'IndexController@close_bid');
Route::get('/create_bid', 'IndexController@bid_no_user');
Route::get('/bidStatus', 'IndexController@bidStatus');



Route::get('admin/login', function (){
    return view('auth.login');
});
Route::get('/admin', function (){
    return redirect('/home');
});

Route::post('/login', 'MyAuthController@postLogin');
Route::get('/logout', 'MyAuthController@getLogout');

Route::group(['middleware'=>'myauth'], function (){
    Route::get('/home', 'AdminController@getSalon');
});

Route::get('/updateUser', 'AdminController@showUpdateUser');

Route::post('/updateUser', 'AdminController@updateUser');
