<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Members;
use App\Models\M3Result;
use App\Models\TampPhone;
use App\Tool\UUID;
use App\Models\M3Email;
use App\Models\TempEmail;
use Illuminate\Support\Facades\Mail;

class MemberController extends Controller
{
  public function register(Request $request)
  {
    $email = $request->input('email', '');
    $phone = $request->input('phone', '');
    $password = $request->input('password', '');
    $confirm = $request->input('confirm', '');
    $phone_code = $request->input('phone_code', '');
    $validate_code = $request->input('validate_code', '');

    $m3_result = new M3Result;

    if ($email == '' && $phone == '') {
      $m3_result->status = 3;
      $m3_result->message = '手机号或邮箱不能为空';
      return $m3_result->toJson();
    }
    if ($password == '' || strlen($password) < 6) {
      $m3_result->status = 3;
      $m3_result->message = '密码不少于6位';
      return $m3_result->toJson();
    }
    if ($confirm == '' || strlen($confirm) < 6) {
      $m3_result->status = 3;
      $m3_result->message = '确认密码不少于6位';
      return $m3_result->toJson();
    }
    if ($password != $confirm) {
      $m3_result->status = 3;
      $m3_result->message = '两次密码不相同';
      return $m3_result->toJson();
    }

    // 手机号注册
    if ($phone != '') {
      if ($phone_code == '' || strlen($phone_code) != 6) {
        $m3_result->status = 3;
        $m3_result->message = '手机验证码为6位';
        return $m3_result->toJson();
      }

      $member = Members::where('phone', $phone)->first();
      if ($member) {
        $m3_result->status = 3;
        $m3_result->message = '该号码已经注册';
        return $m3_result->toJson();
      }

      $tampPhone = TampPhone::where('phone', $phone)->first();
      if ($tampPhone->code == $phone_code) {
        if (time() > strtotime($tampPhone->deadline)) {
          $m3_result->status = 3;
          $m3_result->message = '手机验证码不正确';
          return $m3_result->toJson();
        }

        $member = new Members;
        $member->phone = $phone;
        $member->password = md5($password);
        $member->save();

        $m3_result->status = 0;
        $m3_result->message = '注册成功';
        return $m3_result->toJson();
      } else {
        $m3_result->status = 3;
        $m3_result->message = '手机验证码不正确';
        return $m3_result->toJson();
      }
    } else {
      if ($validate_code == '' || strlen($validate_code) != 4) {
        $m3_result->status = 3;
        $m3_result->message = '验证码为4位';
        return $m3_result->toJson();
      }

      $validate_code_session = $request->session()->get('validate_code', '');
      if ($validate_code_session != $validate_code) {
        $m3_result->status = 3;
        $m3_result->message = '验证码不正确';
        return $m3_result->toJson();
      }

      $isExitis = Members::where('email', $email)->first();
      if ($isExitis) {
        $m3_result->status = 3;
        $m3_result->message = '该邮箱已经注册';
        return $m3_result->toJson();
      }

      $member = new Members;
      $member->email = $email;
      $member->password = md5($password);
      $member->save();

      $uuid = UUID::create();

      $m3_email = new M3Email;
      $m3_email->to = $email;
      $m3_email->cc = '407306742@qq.com';
      $m3_email->subject = '凯恩书店验证';
      $m3_email->content = '请于24小时内点击该链接完成验证. http://laravel.store.com/service/validate_email'
        . '?member_id=' . $member->id
        . '&code=' . $uuid;

      $tempEmail = new TempEmail;
      $tempEmail->member_id = $member->id;
      $tempEmail->code = $uuid;
      $tempEmail->deadline = date('Y-m-d H:i:s', time() + 24 * 60 * 60);
      $bool = $tempEmail->save();

      if ($bool) {
        Mail::send('email_register', ['m3_email' => $m3_email], function ($m) use ($m3_email) {
          $m->to($m3_email->to, '尊敬的用户')
            ->cc($m3_email->cc)
            ->subject($m3_email->subject);
        });

        $m3_result->status = 0;
        $m3_result->message = '注册成功';
        return $m3_result->toJson();
      }
    }
  }


  // 登录验证
  public function login(Request $request)
  {
    $username = $request->input('username');
    $password = $request->input('password');
    $code = $request->input('code');

    $m3_result = new M3Result;

    $validate_code = $request->session()->get('validate_code');
    if ($code != $validate_code) {
      $m3_result->status = 5;
      $m3_result->message = '验证码错误';
      return $m3_result->toJson();
    }

    $member = null;
    if (strpos($username, '@') == true) {
      $member = Members::where('email', $username)->first();
    } else {
      $member = Members::where('phone', $username)->first();
    }

    if (!$member) {
      $m3_result->status = 5;
      $m3_result->message = '该用户不存在';
      return $m3_result->toJson();
    }

    if (md5($password) == $member->password) {
      $request->session()->put('isLogin', true);
      $request->session()->put('user', $member);
      $m3_result->status = 0;
      $m3_result->message = 'success';
      return $m3_result->toJson();
    } else {
      $m3_result->status = 1;
      $m3_result->message = '密码错误';
      return $m3_result->toJson();
    }
  }
}
