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
Route::get('/', 'AuthController@showHomePage');
Route::get('/admin/login', 'AuthController@showHomePage');
Route::get('/admin/register', 'AuthController@showRegisterPage');
Route::post('/admin/user_login', 'AuthController@loginUser');
Route::post('/admin/registerUser', 'AuthController@registerUser');
Route::get('/admin/logout', 'AuthController@logoutUser');

