<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
  public function goLogin(Request $request)
  {
    return view('login')->with('title', '登录')
      ->with('member', $request->session()->get('member', ''));
  }

  public function goRegister()
  {
    return view('register')->with('title', '注册')
      ->with('member', $request->session()->get('member', ''));
  }
}
