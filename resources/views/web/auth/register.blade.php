@extends('web.layouts.master-auth')

@section('header')

@endsection

@section('content')
<div class="main-content">
    <div class="ofs-header">
        <h3 class="ofs-title">用户注册</h3>
    </div>
    <div class="ofs-content-padded">
        <form class="m-t" role="form" accept-charset="UTF-8" method="post" action="{{ route('register', ['recommend' => $recommend]) }}" id="register-form">
            {{ csrf_field() }}
            
            <div class="weui-cells weui-cells_form">
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input name="tel" class="weui-input" placeholder="手机号" required="" id="tel">
                        </div>
                    </div>
                </div>
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input type="password" name="password" class="weui-input" placeholder="密码" required="" id="password">
                        </div>
                    </div>
                </div>
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input type="password" name="password-confirm" class="weui-input" placeholder="重复密码" required="" id="password-confirm">
                        </div>
                    </div>
                </div>
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <div class="weui-btn weui-btn_primary" id="register-btn">注册</div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
    @if($errors->any())
        $.toptip("{{ $errors->first('tel') }}", 1000, 'error');
    @endif

    $('#register-btn').on('click', function() {
        password = $.trim($('#password').val());
        password_confirm = $.trim($('#password-confirm').val());
        tel = $.trim($('#tel').val());
        if(password != password_confirm) {
            $.toptip('前后两次密码不一致！', 1000, 'error');
        } else if(password == '' || tel == '') {
            $.toptip('密码或手机不能为空！', 1000, 'error');
        } else {
            $('#register-form').submit();
        }
    });
});
</script>
@endsection