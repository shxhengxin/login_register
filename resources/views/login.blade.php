@extends('master')
@include('component.loading')
@section('title', '登录')
@section('content')
<div class="weui_cells_title"></div>
<div class="weui_cells weui_cells_form">
  <div class="weui_cell">
      <div class="weui_cell_hd"><label class="weui_label">帐号</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="tel" placeholder="邮箱或手机号" name="accounts"/>
      </div>
  </div>
  <div class="weui_cell">
      <div class="weui_cell_hd"><label class="weui_label">密码</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="password" placeholder="不少于6位" name="password"/>
      </div>
  </div>
  <div class="weui_cell weui_vcode">
      <div class="weui_cell_hd"><label class="weui_label">验证码</label></div>
      <div class="weui_cell_bd weui_cell_primary">
          <input class="weui_input" type="text" placeholder="请输入验证码" name="code"/>
      </div>
      <div class="weui_cell_ft">
          <img src="/service/validate_code/create" class="bk_validate_code"/>
      </div>
  </div>
</div>
<div class="weui_cells_tips"></div>
<div class="weui_btn_area">
  <a class="weui_btn weui_btn_primary" href="javascript:" onclick="onLoginClick();">登录</a>
</div>
<a href="{{ url('/register') }}" class="bk_bottom_tips bk_important">没有帐号? 去注册</a>
@endsection
@section('my-js')
<script type="text/javascript">
    function onLoginClick() {
        var accounts = $('input[name=accounts]').val();
        var password = $('input[name=password]').val();
        var code = $('input[name=code]').val();
        if(accounts == ''){
            $('.bk_toptips').show();
            $('.bk_toptips span').html('请输入邮箱或手机号');
            setTimeout(function(){$('.bk_toptips').hide();},5000);
            return;
        }
        if((!accounts.match(/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/)) && !accounts.match(/^((1[3,5,8][0-9])|(14[5,7])|(17[0,1,6,7,8]))\d{8}$/) ){
            $('.bk_toptips').show();
            $('.bk_toptips span').html('邮箱或手机号格式错误');
            setTimeout(function(){$('.bk_toptips').hide();},5000);
            return;
        }
        if(password == '' || password.length < 6) {
         $('.bk_toptips').show();
         $('.bk_toptips span').html('密码不能为空且不能少于6位');
         setTimeout(function() {$('.bk_toptips').hide();}, 2000);
         return false;
         }
         if(code == '' || code.length != 4){
             $('.bk_toptips').show();
             $('.bk_toptips span').html('验证码不能为空且为4位');
             setTimeout(function() {$('.bk_toptips').hide();}, 2000);
             return false;
         }
        $.ajax({
            url:'login',
            type:'POST',
            dataType: 'json',
            cache: false,
            data:{accouts:accounts,password:password,code:code,_token:'{{ csrf_token() }}'},
            success: function(data){
                if(data == ''){
                    $(".bk_toptips").show();
                    $(".bk_toptips span").html(data.message);
                    setTimeout(function(){$('.bk_toptips').hide();},5000);
                    return;
                }
                if(data.status != 0){
                    $(".bk_toptips").show();
                    $(".bk_toptips span").html(data.message);
                    setTimeout(function(){$('.bk_toptips').hide();},5000);
                    return;
                }
                $(".bk_toptips").show();
                $(".bk_toptips span").html(data.message);
                setTimeout(function(){$('.bk_toptips').hide();},2000);
                return window.location.href = ('/');
            },
            error: function(xhr, status, error) {
                console.log(xhr);
                console.log(status);
                console.log(error);
            }

        });
    }
</script>





<script type="text/javascript">
  $('.bk_validate_code').click(function () {
    $(this).attr('src', '/service/validate_code/create?random=' + Math.random());
  });
</script>
@endsection
