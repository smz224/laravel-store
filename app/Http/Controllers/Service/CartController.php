<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\M3Result;
use App\Models\CartItem;

class CartController extends Controller
{
  public function addCart (Request $request, $product_id)
  {
    $bk_cart = $request->cookie('bk_cart');
    $bk_cart_arr = ($bk_cart != null ? explode(',', $bk_cart) : array());

    $count = 1;
    foreach($bk_cart_arr as &$value) {
      $index = strpos($value, ':');
      if (substr($value, 0, $index) == $product_id) {
        $count = ((int) substr($value, $index + 1)) + 1;
        $value = $product_id . ':' . $count;
        break;
      }
    }

    if ($count == 1) {
      array_push($bk_cart_arr, $product_id . ':' . $count);
    }

    $m3_result = new M3Result;
    $m3_result->status = 0;
    $m3_result->message = '发送成功';

    return response($m3_result->toJson())->withCookie('bk_cart', implode(',', $bk_cart_arr));
  }


  public function deleteCart (Request $request)
  {
    $product_ids = $request->input('product_ids');
    $cart_items = $request->cookie('bk_cart');
    $cart_items_arr = $cart_items != null ? explode(',', $cart_items) : array();
    $member = $request->session()->get('member');

    $m3_result = new M3Result;

    if ($product_ids) {
      foreach($product_ids as $product_id) {
        foreach($cart_items_arr as $key => &$value) {
          $index = strpos($value, ':');
          if (substr($value, 0, $index) == $product_id) {
            array_splice($cart_items_arr, $key, 1);
          }
        }
        if ($member) {
          $bool = CartItem::where([
            ['member_id', $member->id],
            ['product_id', $product_id]
          ])->delete();
          continue;
        }
      }

      $m3_result->status = 0;
      $m3_result->message = '删除成功';
      return Response($m3_result->toJson())->withCookie('bk_cart', implode($cart_items_arr, ','));
    } else {
      $m3_result->status = 1;
      $m3_result->message = '获取信息失败';
      return $m3_result->toJson();
    }
  }
}
