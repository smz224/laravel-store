@extends('master')

@section('title', '书本列表')

@section('content')
  @foreach($products as $product)
  <a class="weui_cell" href="/product/{{$product->id}}">
    <div class="weui_cell_hd"><img class="bk_preview" src="{{$product->preview}}"></div>
    <div class="weui_cell_bd weui_cell_primary">
      <div style="margin-bottom: 10px;">
        <span class="bk_title_1">{{$product->name}}</span>
        <span class="bk_price" style="float: right;">￥ {{$product->price}}</span>
      </div>

      <p class="bk_summary">{{$product->summary}}</p>
    </div>
    <div class="weui_cell_ft"></div>
  </a>
  @endforeach
@stop

@section('my-js')
@stop
