<?php

namespace App\Http\Controllers\View;

use Illuminate\Routing\Controller;
use App\Models\Products;
use App\Models\PdtContents;
use Illuminate\Support\Facades\DB;
use App\Models\PdtImages;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  public function index (Request $request,$category_id) {
    $products = Products::where('category_id', $category_id)->get();
    return view('product', [
      'title' => '书本列表',
      'products' => $products,
      'member'=> $request->session()->get('member', '')
    ]);
  }

  public function getDetail (Request $request, $product_id)
  {
    $detail = DB::table('products as t1')
      ->rightjoin('pdt_contents as t2', 't1.id', '=', 't2.product_id')
      ->select('t1.id', 't1.name', 't1.summary', 't1.price', 't1.preview', 't2.content')
      ->where('t2.product_id', $product_id)
      ->first();

    $bk_cart = $request->cookie('bk_cart');
    $bk_cart_arr = ($bk_cart != null ? explode(',', $bk_cart) : array());

    $count = 0;
    foreach($bk_cart_arr as $value) {
      $index = strpos($value, ':');
      if (substr($value, 0, $index) == $product_id) {
        $count = ((int) substr($value, $index + 1));
        break;
      }
    }

    $pdt_images = PdtImages::where('product_id', $product_id)->get();

    if (!$detail) {
      $detail = Products::where('id', $product_id)->first();
    }

    return view('pdt_content', [
      'title' => $detail->name,
      'detail' => $detail,
      'pdt_images' => $pdt_images,
      'count' => $count,
      'member' => $request->session()->get('member', '')
    ]);
  }
}
