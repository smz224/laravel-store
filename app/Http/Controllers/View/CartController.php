<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\CartItem;
use App\Models\Products;

class CartController extends Controller
{
  public function index (Request $request) {
    $bk_cart = $request->cookie('bk_cart');
    $bk_cart_arr = ($bk_cart != null ? explode(',', $bk_cart) : array());

    $cart_items = array();
    foreach($bk_cart_arr as $key => $value) {
      $index = strpos($value, ':');
      $cart_item = new CartItem;
      $cart_item->id = $key;
//      $cart_item->member_id = $request->session()->get('member')->id;
      $cart_item->product_id = substr($value, 0, $index);
      $cart_item->count = ((int) substr($value, $index + 1));
      $cart_item->product = Products::find($cart_item->product_id);
      if ($cart_item->product) {
        array_push($cart_items, $cart_item);
        //$cart_item->save();
      }
    }

    return view('cart')->with('title', '购物车')
      ->with('cart_items', $cart_items);
  }
}
