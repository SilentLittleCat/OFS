@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.sg-divider-1 {
		width: 100%;
		height: 2px;
		background-color: rgba(0, 0, 0, 0.1);
	}
	.user-info {
		margin-top: 30px;
	}
	.user-info .row {
		font-size: 1.2em;
		margin: 15px 0px;
	}
	.sg-container {
		margin-top: 70px;
	}
</style>
@endsection

@section('content')
<div class="container sg-container">
	<div class="row">
		<div class="col-xs-4 col-sm-4 col-xs-offset-4 col-sm-offset-4 sg-centered">
			<i class="fa fa-user fa-4x" aria-hidden="true"></i>
			<div>{{ Auth::user()->wechat_name }}</div>
		</div>
	</div>
	<div class="user-info">
		<div class="row">
			<div class="col-xs-4 col-sm-4 col-xs-offset-1">
				姓名
			</div>
			<div class="col-xs-6 col-sm-6 sg-centered">
				{{ Auth::user()->real_name }}
			</div>
		</div>
		<div class="sg-divider-1"></div>
		<div class="row">
			<div class="col-xs-4 col-sm-4 col-xs-offset-1">
				性别
			</div>
			<div class="col-xs-6 col-sm-6 sg-centered">
				{{ Auth::user()->gender == 2 ? '女' : (Auth::user()->gender == 1 ? '男' : '') }}
			</div>
		</div>
		<div class="sg-divider-1"></div>
		<div class="row">
			<div class="col-xs-4 col-sm-4 col-xs-offset-1">
				生日
			</div>
			<div class="col-xs-6 col-sm-6 sg-centered">
				{{ Auth::user()->birthday }}
			</div>
		</div>
		<div class="sg-divider-1"></div>
		<div class="row">
			<div class="col-xs-4 col-sm-4 col-xs-offset-1">
				体重
			</div>
			<div class="col-xs-6 col-sm-6 sg-centered">
				{{ Auth::user()->weight == null ? '' : Auth::user()->weight . 'KG' }}
			</div>
		</div>
		<div class="sg-divider-1"></div>
		<div class="row">
			<div class="col-xs-4 col-sm-4 col-xs-offset-1">
				身高
			</div>
			<div class="col-xs-6 col-sm-6 sg-centered">
				{{ Auth::user()->height == null ? '' :  Auth::user()->height . 'CM' }}
			</div>
		</div>
		<div class="sg-divider-1"></div>
		<div class="row">
			<div class="col-xs-4 col-sm-4 col-xs-offset-1">
				手机号
			</div>
			<div class="col-xs-6 col-sm-6 sg-centered">
				{{ Auth::user()->tel }}
			</div>
		</div>
		<div class="row">
			<div class="col-xs-10 col-sm-10 col-xs-offset-1 col-sm-offset-1">
				<button class="weui-btn weui-btn_primary" id="edit-user-info">修改资料</button>
				<button class="weui-btn weui-btn_warn" id="weight-info">体重表</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#edit-user-info').on('click', function() {
		window.location = "{{ route('users.edit') }}";
	});

	$('#weight-info').on('click', function() {
		window.location = "{{ route('users.weight') }}";
	});
});
</script>
@endsection