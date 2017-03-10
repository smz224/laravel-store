<?php

namespace App\Http\Controllers\Service;

use Illuminate\Routing\Controller;
use App\Models\M3Result;
use App\Models\Categorys;

class CategoryController extends Controller
{
  public function getCategorys ($parent_id) {
    $sub_categorys = Categorys::where('parent_id', $parent_id)->get();
    $m3_result = new M3Result;

    if ($sub_categorys) {
      $m3_result->status = 0;
      $m3_result->message = 'SUCCESS';
      $m3_result->categorys = $sub_categorys;
      return $m3_result->toJson();
    } else {
      $m3_result->status = 1;
      $m3_result->message = 'FAIL';
      return $m3_result->toJson();
    }
  }
}
