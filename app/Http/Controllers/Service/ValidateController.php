<?php

namespace App\Http\Controllers\Service;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Tool\Validate\ValidateCode;
use App\Tool\SMS\SendTemplateSMS;
use App\Models\M3Result;
use App\Models\TampPhone;

class ValidateController extends Controller
{
        protected $code;

		public function create ($value="") {
			$validateCode = new ValidateCode;
			return $validateCode->doimg();
		}

		public function sendSMS (Request $request) {
			$m3_result = new M3Result;

			$phone = $request->input('phone', '');
			if ($phone == '') {
				$m3_result->status = 1;
				$m3_result->message = '手机号不能为空';
				return $m3_result->toJson();
			}

            $tampPhone = TampPhone::where('phone', $phone)->first();

			// 如果是第一次发送验证码或者是验证码过期,则创建一个新的随机验证码
			if (!$tampPhone || time() > strtotime($tampPhone->deadline)) {
                $code = '';
                $charset = '1234567890';
                $_len = strlen($charset) - 1;
                for ($i = 0; $i < 6; $i++) {
                    $code .= $charset[mt_rand(0, $_len)];
                }
                $this->code = $code;
			} else {
			    $this->code = $tampPhone->code;
            }

            $sendTemplateSMS = new SendTemplateSMS;
            $result = $sendTemplateSMS->sendTemplateSMS($phone, array($this->code, '60'), "1");

			if ($result->statusCode != 0) {
				$m3_result->status = 2;
				$m3_result->message = "容联云通讯服务器错误";
				return $m3_result->toJson();
			}
			else{
			    $timestamp = time();
			    $item = TampPhone::where('phone', $phone)->first();
			    if ( $item  && $this->code != $item->code ) {
                    $result->delete();
                }
                else if ($item && $this->code == $item->code ) {
			        $m3_result->status = 0;
			        $m3_result->message = '发送成功';
			        return $m3_result->toJson();
                }

				$tampPhone = new TampPhone;
				$tampPhone->phone = $phone;
				$tampPhone->code = $code;
				$tampPhone->deadline = date('Y-m-d H:i:s', $timestamp + 60 * 60);
				$bool = $tampPhone->save();

				if ($bool) {
					$m3_result->status = 0;
					$m3_result->message = "发送成功";
					return $m3_result->toJson();
				}
			}
		}
}
