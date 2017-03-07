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

			$sendTemplateSMS = new SendTemplateSMS;
			$code = '';
			$charset = '1234567890';
			$_len = strlen($charset) - 1;
			for ($i = 0; $i < 6; $i++ ) {
				$code .= $charset[mt_rand(0, $_len)];
			} 

			$result = $sendTemplateSMS->sendTemplateSMS($phone, array($code, '60'), "1");

			if ($result->statusCode != 0) {
				$m3_result->status = 2;
				$m3_result->message = "容联云通讯服务器错误";
				return $m3_result->toJson();
			}
			else{
				$tampPhone = new TampPhone;
				$tampPhone->phone = $phone;
				$tampPhone->code = $code;
				$tampPhone->deadline = date('Y-m-d H-i-s', time() + 60 * 60);
				$bool = $tampPhone->save();

				if ($bool) {
					$m3_result->status = 0;
					$m3_result->message = "发送成功";
					return $m3_result->toJson();
				}
			}
		}
}
