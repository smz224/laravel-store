<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Categorys;

class CategoryController extends Controller
{
  public function index (Request $request)
  {
    $member = $request->session()->get('member');
    $categorys = Categorys::whereNull('parent_id')->get();
    return view('category', [
      'member' => $member,
      'title' => '书籍分类',
      'categorys' => $categorys
    ]);
  }
}
