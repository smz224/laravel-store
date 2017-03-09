<?php
/**
 * Created by PhpStorm.
 * User: yy
 * Date: 2017/3/8
 * Time: 下午11:04
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class M3Email extends Model
{
  public $from;  // 发件人邮箱
  public $to; // 收件人邮箱
  public $cc; // 抄送
  public $attach; // 附件
  public $subject; // 主题
  public $content; // 内容
}
