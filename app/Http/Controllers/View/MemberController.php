<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
  public function goLogin()
  {
    return view('login', [
      'title' => '登录'
    ]);
  }

  public function goRegister()
  {
    return view('register', [
      'title' => '注册'
    ]);
  }
}
