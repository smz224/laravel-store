<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class CategoryController extends Controller
{
  public function index () {
    return view('category');
  }
}
