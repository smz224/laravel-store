<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoryController extends Controller
{
  public function index (Request $request) {
    $member = $request->session()->get('member');
    return view('category', [
      'member' => $member,
      'title' => '书籍分类'
    ]);
  }
}
