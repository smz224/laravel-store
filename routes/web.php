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
  return view('login', ['title' => '登录']);
});

Route::group(['prefix' => '/'], function () {
  Route::get('login', 'View\MemberController@goLogin');
  Route::get('register', 'View\MemberController@goRegister');
  Route::get('category', 'View\CategoryController@index');
});

Route::group(['prefix' => 'service'], function () {
  Route::get('validate_code/create', 'Service\ValidateController@create');
  Route::get('validate_phone/send', 'Service\ValidateController@sendSMS');
  Route::get('validate_email', 'Service\ValidateController@validateEmail');
  Route::post('login', 'Service\MemberController@login');
  Route::post('register', 'Service\MemberController@register');
});
