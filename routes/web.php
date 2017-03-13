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
  return view('login')->with('title', '注册')
    ->with('member', $request->session()->get('member', ''));
});

Route::group(['prefix' => '/'], function () {
  Route::get('login', 'View\MemberController@goLogin');
  Route::get('register', 'View\MemberController@goRegister');
  Route::get('category', 'View\CategoryController@index');
  Route::get('product/category_id/{category_id}', 'View\ProductController@index');
  Route::get('product/{product_id}', 'View\ProductController@getDetail');
  Route::get('cart', 'View\CartController@index');
});

Route::group(['prefix' => '/', 'middleware' => 'check.login'], function () {
  Route::get('order_pay', 'View\OrderController@toOrderPay');
});

Route::group(['prefix' => 'service'], function () {
  Route::get('validate_code/create', 'Service\ValidateController@create');
  Route::get('validate_phone/send', 'Service\ValidateController@sendSMS');
  Route::get('validate_email', 'Service\ValidateController@validateEmail');
  Route::post('login', 'Service\MemberController@login');
  Route::get('logout', 'Service\MemberController@logout');
  Route::post('register', 'Service\MemberController@register');
  Route::get('category/parent_id/{id}', 'Service\CategoryController@getCategorys');
  Route::get('cart/add/{product_id}', 'Service\CartController@addCart');
  Route::get('cart/delete', 'Service\CartController@deleteCart');
});
