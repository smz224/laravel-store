<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Orders;
use App\Models\OrderList;
use App\Models\Products;

class OrderController extends Controller
{
  public function toOrderPay (Request $request, $product_ids) {
    $member = $request->session()->get('member');
    $product_ids_arr = $product_ids ? explode(',', $product_ids) : array();
    $pay_items_arr = array();
    $total_price = null;

    if ($member) {
      $products_arr = DB::table('cart_item as t1')
        ->join('products as t2', 't1.product_id', '=', 't2.id')
        ->select('t2.id', 't2.name', 't2.preview', 't2.price', 't1.count')
        ->where('t1.member_id', $member->id)
        ->get();

      foreach($product_ids_arr as $product_id) {
        foreach($products_arr as $product) {
          if ($product_id == $product->id) {
            array_push($pay_items_arr, $product);
            $total_price += (int) ($product->count * $product->price);
          }
        }
      }
    }

    return view('order_pay')->with('title', '订单支付')
      ->with('member', $member)
      ->with('pay_items', $pay_items_arr)
      ->with('total_price', $total_price);
  }

  public function toOrderList (Request $request) {
    $member = $request->session()->get('member');
    $orders = Orders::where('member_id', $member->id)->get();
    foreach($orders as $order) {
      $order_items = OrderList::where('order_id', $order->order_no)->get();
      $order->order_items = $order_items;
      foreach($order_items as $order_item) {
        $order_item->product = Products::find($order_item->product_id);
      }
    }

    return view('order_list')
      ->with('title', '订单列表')
      ->with('member', $member)
      ->with('orders', $orders);
  }
}
