<?php

namespace App\Http\Controllers\View;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MemberController extends Controller
{
    public function goLogin () {
    	return view('login');
    }

    public function goRegister () {
    	return view('register');
    }
}
