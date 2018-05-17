@extends('web.layouts.master-auth')

@section('header')

@endsection

@section('content')
<div class="main-content">
    <div class="ofs-header">
        <h3 class="ofs-title">用户登录</h3>
    </div>
    <div class="ofs-content-padded">
        <form class="m-t" role="form" accept-charset="UTF-8" method="post" action="{{ route('login') }}">
            {{ csrf_field() }}
            
            <div class="weui-cells weui-cells_form">
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input name="tel" class="weui-input" placeholder="手机号" required="">
                        </div>
                    </div>
                </div>
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input type="password" name="password" class="weui-input" placeholder="密码" required="">
                        </div>
                    </div>
                </div>
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <button type="submit" class="weui-btn weui-btn_primary">登录</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('footer')

@endsection