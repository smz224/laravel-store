<?php

namespace App\Http\Controllers\View;

use Illuminate\Routing\Controller;
use App\Models\Products;
use App\Models\PdtContents;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
  public function index ($category_id) {
    $products = Products::where('category_id', $category_id)->get();
    return view('product', [
      'title' => '书本列表',
      'products' => $products
    ]);
  }

  public function getDetail ($product_id)
  {
    $detail = DB::table('products as t1')
      ->rightjoin('pdt_contents as t2', 't1.id', '=', 't2.product_id')
      ->select('t1.id', 't1.name', 't1.summary', 't1.price', 't1.preview', 't2.content')
      ->where('t2.product_id', $product_id)
      ->first();

    return view('pdt_content', [
      'title' => $detail->name,
      'detail' => $detail
    ]);
  }
}
