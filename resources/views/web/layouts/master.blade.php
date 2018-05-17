<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ isset($title) ? $title : '点餐系统' }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="/base/css/bootstrap.min.css?v=3.4.0.css" rel="stylesheet">
    <link href="/base/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/base/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="/base/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="/base/css/plugins/jquery-weui/jquery-weui.min.css" rel="stylesheet">
    <link href="/base/css/plugins/jquery-weui/weui.min.css" rel="stylesheet">
    <link href="/base/css/plugins/bootstrap-validator/bootstrapValidator.css" rel="stylesheet">

   	<!-- 全局js -->
    <script src="/base/js/jquery-2.1.1.min.js?v={{ config('sys.version') }}" ></script>
    <script src="/base/js/bootstrap.min.js?v=3.4.0" ></script>

    <style type="text/css">
    	body {

    	}
    	.top-nav {
    		width: 100%;
    		padding: 10px;
    		background-color: #888;
    		color: white;
    		position: fixed;
    		top: 0px;
    		z-index: 1000;
    	}
		.main-content {
			margin-top: 50px;
			margin-bottom: 80px;
		}
		.sg-money {
			font-size: 1.8em;
			color: red;
			font-weight: bold;
		}
		.sg-money-sm {
			font-size: 1.2em;
			color: red;
			font-weight: bold;
		}
		.sg-divider {
			width: 100%;
			height: 2px;
			background-color: rgba(0, 0, 0, 0.1);
			margin: 25px 0;
		}
		.sg-divider-none {
			width: 100%;
			background-color: rgba(0, 0, 0, 0);
			margin: 20px 0;
		}
		.sg-divider-light {
			width: 100%;
			height: 1px;
			background-color: rgba(0, 0, 0, 0.1);
			margin: 10px 0;
		}
		.sg-divider-bold {
			width: 100%;
			height: 3px;
			background-color: rgba(0, 0, 0, 0.1);
			margin: 20px 0;
		}
		.sg-divider-1 {
			width: 100%;
			height: 2px;
			background-color: rgba(0, 0, 0, 0.1);
		}
		.sg-container {
			margin: 50px 10px;
		}
		.sg-centered {
			text-align: center;
		}
		.link-style {
			color: white;
			text-decoration: none;
		}
		.link-style:hover {
			color: white;
			text-decoration: none;
		}
		.link-none {
			text-decoration: none;
		}
		.link-none:hover {
			text-decoration: none;
		}
		.sg-food-kind {
			font-weight: bold;
			font-size: 1.2em;
		}
		.success-color {
			color: #57b658;
		}
		.danger-color {
			color: #d8534c;
		}
		.warning-btn-color {
			background-color: #f2ad50;
			color: white;
		}
		.sg-strong {
			font-size: 1.5em;
			font-weight: bold;
		}
		.red-strong {
			color: red;
			font-weight: bold;
			font-size: 1.2em;
		}
		.sg-light {
			font-weight: lighter;
		}
		.sg-light-1 {
			color: #888;
		}
		.row {
			margin-top: 10px;
			margin-bottom: 10px;
		}
		.sg-time {
			color: #888;
			font-size: 0.8em;
		}
		.bottom-navigation {
			text-align: center;
			display: inline-block;
			font-size: 0.8em;
			width: 100%;
		}
		.bottom-navigation .navbar-nav {
			margin: 0px;
			width: 100%;
		}
		.bottom-navigation .bottom-nav-item {
			width: 24%;
			margin: 0px;
			display: inline-block;
		}
		.sg-hide {
			display: none;
		}
		.ofs-bottom-nav a {
			text-decoration: none;
		}
		.weui-tabbar .weui-bar__item--on .weui-tabbar__icon i {
			color: #04BE02;
		}
		.ofs-title {
			text-align: center;
		}
    </style>

    @yield('header')
</head>

<body id="app" ontouchstart>

	<div class="top-nav">
		<div class="content">
			@if(isset($back_url))
				<a href="{{ $back_url }}">
					<i class="fa fa-arrow-left fa-lg" aria-hidden="true" style="color: white"></i>
				</a>
			@endif
			{{ isset($page_label) ? $page_label : '' }}
			<div class="pull-right">
				@if(Auth::check())
					<div class="dropdown">
						<button type="button" class="btn btn-sm btn-info dropdown-toggle" id="logout-dropdown" data-toggle="dropdown">
							{{ Auth::user()->wechat_name == null ? Auth::user()->tel : Auth::user()->wechat_name }}<span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu" aria-labelledby="logout-dropdown">
							<li role="presentation">
            					<a role="menuitem" tabindex="-1" href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">退出</a>
        					</li>
        					<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
						</ul>
					</div>
				@else
					<div class="btn btn-sm btn-success" id="login-btn">登录</div>
                    <div class="btn btn-sm btn-info" id="register-btn">注册</div>
				@endif
			</div>
		</div>
	</div>

	<div class="main-content">
		@yield('content')
	</div>

	@if(!isset($hide_bottom) && isset($page))
		<div class="weui-tabbar ofs-bottom-nav" style="position: fixed; bottom: 0px; padding-top: 5px;">
			<a href="{{ route('index') }}" class="weui-tabbar__item {{ $page == 'index' ? 'weui-bar__item--on' : '' }}">
				<div class="weui-tabbar__icon">
					<i class="fa fa-home fa-lg" aria-hidden="true"></i>
				</div>
				<p class="weui-tabbar__label">首页</p>
			</a>
			<a href="{{ route('orders.send') }}" class="weui-tabbar__item {{ $page == 'send' ? 'weui-bar__item--on' : '' }}">
				<div class="weui-tabbar__icon">
					<i class="fa fa-cutlery fa-lg" aria-hidden="true"></i>
				</div>
				<p class="weui-tabbar__label">配送</p>
			</a>
<!-- 			<a href="{{ route('food.introduce') }}" class="weui-tabbar__item {{ $page == 'introduce' ? 'weui-bar__item--on' : '' }}">
				<div class="weui-tabbar__icon">
					<i class="fa fa-cutlery fa-lg" aria-hidden="true"></i>
				</div>
				<p class="weui-tabbar__label">餐类介绍</p>
			</a> -->
<!-- 			<a href="{{ route('activity') }}" class="weui-tabbar__item {{ $page == 'activity' ? 'weui-bar__item--on' : '' }}">
				<div class="weui-tabbar__icon">
					<i class="fa fa-vimeo-square fa-lg" aria-hidden="true"></i>
				</div>
				<p class="weui-tabbar__label">会员活动</p>
			</a> -->
			<a href="{{ route('users.index') }}" class="weui-tabbar__item {{ $page == 'user' ? 'weui-bar__item--on' : '' }}">
				<div class="weui-tabbar__icon">
					<i class="fa fa-user fa-lg" aria-hidden="true"></i>
				</div>
				<p class="weui-tabbar__label">我的</p>
			</a>
		</div>
	@endif

    <!-- jQuery Validation plugin javascript-->
    <script src="/base/js/plugins/validate/jquery.validate.min.js?v={{ config('sys.version') }}" ></script>
    <script src="/base/js/plugins/validate/validate_custom.js?v={{ config('sys.version') }}" ></script>
    <script src="/base/js/plugins/validate/messages_zh.min.js?v={{ config('sys.version') }}" ></script>
    <script src="/base/js/plugins/datapicker/bootstrap-datepicker.js" ></script>
    <script src="/base/js/plugins/datetimepicker/bootstrap-datetimepicker.js" ></script>
    <script src="/base/js/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js" ></script>
    <script src="/base/js/plugins/jquery-weui/jquery-weui.min.js" ></script>
    <script src="/base/js/plugins/bootstrap-validator/bootstrapValidator.js" ></script>
    <script src="/js/echarts.min.js" ></script>

    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
        });
		$.validator.setDefaults({
			highlight : function(a) {
				$(a).closest(".form-group").removeClass(
						"has-success").addClass("has-error")
			},
			success : function(a) {
				a.closest(".form-group").removeClass("has-error")
						.addClass("has-success")
			},
			errorElement : "span",
			errorPlacement : function(a, b) {
				if (b.is(":radio") || b.is(":checkbox")) {
					a.appendTo(b.parent().parent().parent())
				} else {
					a.appendTo(b.parent())
				}
			},
			errorClass : "help-block m-b-none",
			validClass : "help-block m-b-none"
		});

		$('#login-btn').on('click', function() {
			window.location = "{{ route('login') }}";
		});
		$('#register-btn').on('click', function() {
			window.location = "{{ route('register') }}";
		});
	</script>
	
    @yield('footer')
</body>

</html>