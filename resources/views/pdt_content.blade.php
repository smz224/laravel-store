@extends('master')

@section('title', '{{ $pdt_content }}')

@section('content')
  <div class="page bk_content" style="top: 0;">
    <div class="weui_cells_title">
      <span class="bk_title_1">{{$detail->name}}</span>
      <span class="bk_price" style="float: right">￥ {{$detail->price}}</span>
    </div>
    <div class="weui_cells">
      <div class="weui_cell">
        <p class="bk_summary">{{$detail->summary}}</p>
      </div>
    </div>

    <div class="weui_cells_title">详细介绍</div>
    <div class="weui_cells">
      <div class="weui_cell">
        @if($detail->content)
          {!! $detail->content !!}
        @else

        @endif
      </div>
    </div>
  </div>

  <div class="bk_fix_bottom">
    <div class="bk_half_area">
      <button class="weui_btn weui_btn_primary" onclick="_addCart();">加入购物车</button>
    </div>
    <div class="bk_half_area">
      <button class="weui_btn weui_btn_default" onclick="_toCart()">结算(<span id="cart_num" class="m3_price"></span>)</button>
    </div>
  </div>
@stop

@section('my-js')
@stop
