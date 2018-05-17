@extends('web.layouts.master')

@section('header')
<style type="text/css">
	#remain-money {
		margin-top: 20px;
	}
	.user-info-item {
		font-size: 1.2em;
		margin: 15px 0px;
	}
	.user-info-item a {
		text-decoration: none;
	}
	.sg-container {
		margin-top: 70px;
	}
	.user-info-item-new {
		border-top: 2px solid #eee;
		font-size: 1.2em;
		padding-top: 10px;
		padding-bottom: 10px;
	}
	.user-info-item-new:last-child {
		border-bottom: 2px solid #eee;
	}
	.user-info-item-new a {
		color: black;
		text-decoration: none;
	}
	.user-info-item-new .right-item i {
		float: right;
		font-size: 1.4em;
	}
	.user-info-item-new .left-item i {
		margin-right: 10px;
		font-size: 1.3em;
	}
	.row.user-info-item-new {
		margin-top: 0px;
		margin-bottom: 0px;
	}
	.sg-container .sg-header {
		margin-bottom: 40px;
	}
	.remain-times {
		color: grey;
		font-size: 0.8em;
	}
</style>
@endsection

@section('content')
<div class="container sg-container" id="sg-content">
	<div class="row sg-header">
		<div class="col-xs-4 col-sm-4 col-xs-offset-4 col-sm-offset-4 sg-centered">
			<i class="fa fa-user fa-4x" aria-hidden="true"></i>
			<div>{{ Auth::user()->wechat_name }}</div>
			<div class="remain-times">
				{{ '剩余' . (Auth::user()->man_remain_times + Auth::user()->woman_remain_times + Auth::user()->work_remain_times) . '次' }}
			</div>
		</div>
	</div>
	<div class="row user-info-item-new my-orders">
		<div class="left-item col-xs-8 col-sm-8 col-xs-8">
			<i class="fa fa-user-circle-o" aria-hidden="true"></i>
			<span>我的订单</span>
		</div>
		<div class="right-item col-xs-4 col-sm-4 col-xs-4">
			<i class="fa fa-angle-right" aria-hidden="true"></i>
		</div>
	</div>
	<div class="row user-info-item-new contact-us">
		<div class="left-item col-xs-8 col-sm-8 col-xs-8">
			<i class="fa fa-phone-square" aria-hidden="true"></i>
			<span>联系我们</span>
		</div>
		<div class="right-item col-xs-4 col-sm-4 col-xs-4">
			<i class="fa fa-angle-right" aria-hidden="true"></i>
		</div>
	</div>
<!-- 	<div class="row">
		<div class="col-xs-6 col-sm-6 sg-centered" id="remain-money">
			<div>我的余额：</div>
			<div>
				<span class="sg-money">{{ Auth::user()->remain_money }}</span>&nbsp;&nbsp;&nbsp;元
			</div>
		</div>
		<div class="col-xs-6 col-sm-6">
			<div class="btn btn-sm btn-success btn-block" id="pay-money">充值</div>
			<div class="btn btn-sm btn-info btn-block" id="back-money">提现</div>
		</div>
	</div>
	<div class="row user-info-item" id="user-info">
		<div class="col-xs-8 col-sm-8 col-xs-offset-2 col-sm-offset-2 sg-centered">
			<a href="{{ route('users.info') }}">我的资料</a>
		</div>
	</div>
	<div class="sg-divider-1"></div>
	<div class="row user-info-item">
		<div class="col-xs-8 col-sm-8 col-xs-offset-2 col-sm-offset-2 sg-centered">
			<a href="{{ route('orders.index') }}">我的订单</a>
		</div>
	</div>
	<div class="sg-divider-1"></div>
	<div class="row user-info-item">
		<div class="col-xs-8 col-sm-8 col-xs-offset-2 col-sm-offset-2 sg-centered">
			<a href="{{ route('users.address') }}">我的收货地址</a>
		</div>
	</div>
	<div class="sg-divider-1"></div>
	<div class="row user-info-item">
		<div class="col-xs-8 col-sm-8 col-xs-offset-2 col-sm-offset-2 sg-centered">
			<a href="{{ route('users.score') }}">积分商城</a>
		</div>
	</div>
	<div class="sg-divider-1"></div>
	<div class="row user-info-item">
		<div class="col-xs-8 col-sm-8 col-xs-offset-2 col-sm-offset-2 sg-centered">
			<a href="{{ route('users.coupon') }}">我的优惠券</a>
		</div>
	</div>
	<div class="sg-divider-1"></div>
	<div class="row user-info-item">
		<div class="col-xs-8 col-sm-8 col-xs-offset-2 col-sm-offset-2 sg-centered">
			<a href="{{ route('users.recommend') }}">推荐有礼</a>
		</div>
	</div>
	<div class="sg-divider-1"></div>
	<div class="row user-info-item">
		<div class="col-xs-8 col-sm-8 col-xs-offset-2 col-sm-offset-2 sg-centered">
			<a href="{{ route('users.remain.money') }}">余额流水</a>
		</div>
	</div>
	<div class="sg-divider-1"></div>
	<div class="row user-info-item">
		<div class="col-xs-8 col-sm-8 col-xs-offset-2 col-sm-offset-2 sg-centered">
			<a href="{{ route('users.contact') }}">联系我们</a>
		</div>
	</div> -->
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#pay-money').on('click', function() {
		url = "{{ route('money.pay.index') }}";
		window.location = url;
	});

	$('#back-money').on('click', function() {
		url = "{{ route('money.back.index') }}";
		window.location = url;
	});

	$('#sg-content').on('click', '.my-orders', function() {
		window.location = "{{ route('orders.index') }}";
	}).on('click', '.contact-us', function() {
		window.location = "{{ route('users.contact') }}";
	});
});
</script>
@endsection