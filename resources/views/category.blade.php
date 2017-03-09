@extends('master')

@section('title', '书籍分类')

@section('content')
  @if ($member)
    <p>欢迎你,{{ $member->nickname }}</p>
  @else
    <p>请登录</p>
  @endif
@stop

@section('my-js')

@stop
