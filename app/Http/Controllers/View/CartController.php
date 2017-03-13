<?php

namespace App\Http\Controllers\View;

use App\Models\M3Result;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\CartItem;
use App\Models\Products;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
  public function index (Request $request)
  {
    $bk_cart = $request->cookie('bk_cart');
    $bk_cart_arr = ($bk_cart != null ? explode(',', $bk_cart) : array());
    $member = $request->session()->get('member');

    $cart_items_arr = array();
    foreach($bk_cart_arr as $value) {
      $index = strpos($value, ':');
      $cart_item = new CartItem;
      $cart_item->product_id = substr($value, 0, $index);
      $cart_item->count = ((int) substr($value, $index + 1));
      array_push($cart_items_arr, $cart_item);
      if ($member) {
        $this->cartSync($member, $cart_item);
        continue;
      }
    }

    if ($member) {
      $cart_items = DB::table('cart_item as t1')
        ->join('products as t2', 't1.product_id', '=', 't2.id')
        ->select('t1.id', 't2.name', 't2.preview', 't2.price', 't1.count', 't1.product_id')
        ->where('t1.member_id', $member->id)
        ->get();
    } else {
      foreach($cart_items_arr as &$value) {
        $product_id = $value->product_id;
        $product = Products::where('id', $product_id)->first();
        $value->name = $product->name;
        $value->preview = $product->preview;
        $value->price = $product->price;
      }
      $cart_items = $cart_items_arr;
    }

//    return $cart_items;

    return view('cart')->with('title', '购物车')
      ->with('cart_items', $cart_items)
      ->with('member', $request->session()->get('member', ''));
  }

  private function cartSync ($member, $cart_item)
  {
    $member_id = $member->id;
    $db_cart_item = CartItem::where([
      ['member_id', $member_id],
      ['product_id', $cart_item->product_id]
    ])->first();


    if ($db_cart_item) {
      $count = $db_cart_item->count >= $cart_item->count ? $db_cart_item->count : $cart_item->count;
      $db_cart_item->count = $count;
      $db_cart_item->save();
    } else {
      $cart_item->member_id = $member_id;
      $cart_item->save();
    }
  }
}
