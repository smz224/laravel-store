<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class OrderController extends Controller
{
  public function toOrderPay (Request $request) {
    return view('order_pay')->with('title', '账单支付')
      ->with('member', $request->session()->get('member', ''));
  }
}
