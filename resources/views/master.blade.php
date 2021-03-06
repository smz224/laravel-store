<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" ,
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield("title")</title>
  <link rel="stylesheet" type="text/css" href="/css/weui.css">
  <link rel="stylesheet" type="text/css" href="/css/book.css">
</head>
<body>

<div class="page">
  @yield("content")
</div>

<!-- tooltips -->
<div class="bk_toptips"><span></span></div>

{{--<div id="global_menu" onclick="onMenuClick();">--}}
{{--<div></div>--}}
{{--</div>--}}

<div class="bk_title clearfix">
  <div class="bk_back">
    <span class="icon-arrow_back" onclick="_goBack()"></span>
  </div>
  <div class="bk_more" onclick="onMenuClick()">
    <span class="icon-more_horiz"></span>
  </div>
  <div class="bk_title_content">
    <span>{{ $title }}</span>
  </div>
</div>

<!--BEGIN actionSheet-->
<div id="actionSheet_wrap">
  <div class="weui_mask_transition" id="mask"></div>
  <div class="weui_actionsheet" id="weui_actionsheet">
    <div class="weui_actionsheet_menu">
      <div class="weui_actionsheet_cell" onclick="onMenuItemClick(1)">书籍分类</div>
      <div class="weui_actionsheet_cell" onclick="onMenuItemClick(2)">订单列表</div>
      <div class="weui_actionsheet_cell" onclick="onMenuItemClick(3)">购物车</div>
      @if ($member)
        <div class="weui_actionsheet_cell" style="color: #FF4834;" onclick="_onLogout()">用户注销</div>
      @endif
    </div>
    <div class="weui_actionsheet_action">
      <div class="weui_actionsheet_cell" id="actionsheet_cancel">取消</div>
    </div>
  </div>
</div>

<script type="text/javascript" src="/js/jquery-1.11.2.min.js"></script>
<script type="text/javascript">

  function hideActionSheet(weuiActionsheet, mask) {
    weuiActionsheet.removeClass('weui_actionsheet_toggle');
    mask.removeClass('weui_fade_toggle');
    weuiActionsheet.on('transitionend', function () {
      mask.hide();
    }).on('webkitTransitionEnd', function () {
      mask.hide();
    })
  }

  function onMenuClick() {
    var mask = $('#mask');
    var weuiActionsheet = $('#weui_actionsheet');
    weuiActionsheet.addClass('weui_actionsheet_toggle');
    mask.show().addClass('weui_fade_toggle').click(function () {
      hideActionSheet(weuiActionsheet, mask);
    });
    $('#actionsheet_cancel').click(function () {
      hideActionSheet(weuiActionsheet, mask);
    });
    weuiActionsheet.unbind('transitionend').unbind('webkitTransitionEnd');
  }

  function onMenuItemClick(index) {
    var mask = $('#mask');
    var weuiActionsheet = $('#weui_actionsheet');
    hideActionSheet(weuiActionsheet, mask);
    if (index == 1) {
      location.href = '/category';
    } else if (index == 2) {
      location.href = '/order_list';
    } else if (index == 3) {
      location.href = "/cart";
    } else {
      $('.bk_toptips').show();
      $('.bk_toptips span').html("敬请期待!");
      setTimeout(function () {
        $('.bk_toptips').hide();
      }, 2000);
    }
  }

  function _goBack() {
    if (location.pathname == "/category") {
      console.log(1);
      location.replace(document.referrer)
    } else {
      console.log(2);
      history.go(-1)
    }
  }

  function _onLogout() {

    var mask = $('#mask');
    var weuiActionsheet = $('#weui_actionsheet');
    hideActionSheet(weuiActionsheet, mask);

    $.ajax({
      type: 'GET',
      url: '/service/logout',
      dataType: 'json',
      cache: false,
      success: function (data) {
        if (!data) {
          $('.bk_toptips').show();
          $('.bk_toptips span').html('服务端错误');
          setTimeout(function () {
            $('.bk_toptips').hide();
          }, 2000);
          return;
        }

        if (data.status != 0) {
          $('.bk_toptips').show();
          $('.bk_toptips span').html(data.message);
          setTimeout(function () {
            $('.bk_toptips').hide();
          }, 2000);
          return;
        }

        $('.bk_toptips').show();
        $('.bk_toptips span').html('注销成功');
        setTimeout(function () {
          $('.bk_toptips').hide();
        }, 2000);

        location.reload();
      }
    })
  }
</script>

@yield('my-js')
</body>
</html>
