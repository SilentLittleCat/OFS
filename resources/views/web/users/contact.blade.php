@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.user-info-item-new {
		border-top: 2px solid #eee;
		border-bottom: 2px solid #eee;
		font-size: 1.2em;
		padding-top: 10px;
		padding-bottom: 10px;
	}
	.user-info-item-new a {
		color: black;
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
	.sg-green {
		color: #1abc9c;
		font-size: 1.2em;
	}
</style>
@endsection

@section('content')
<div class="container sg-container">
	<div class="row user-info-item-new" id="after-sale">
		<div class="left-item col-xs-8 col-sm-8 col-xs-8">
			<i class="fa fa-user-circle-o" aria-hidden="true"></i>
			<span>售后订单</span>
		</div>
		<div class="right-item col-xs-4 col-sm-4 col-xs-4">
			<i class="fa fa-angle-right" aria-hidden="true"></i>
		</div>
	</div>
	<div class="sg-divider-none"></div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 sg-centered">
			用餐遇到问题？<a href="{{ route('orders.apply.after.sale') }}">点击申请售后服务</a>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 sg-centered sg-green">
			OR
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 sg-centered sg-green">
			直接联系我们
		</div>
	</div>
<!-- 	<div class="row">
		<div class="col-xs-12 col-sm-12 sg-centered sg-strong">
			联系我们
		</div>
	</div> -->
	<div class="sg-divider-none"></div>
	<div class="row">
		<div class="col-xs-4 col-sm-4">
			联系热线：
		</div>
		<div class="col-xs-8 col-sm-8">
			<a href="tel:{{ $tel != null ? $tel->val : '' }}">{{ $tel != null ? $tel->val : '' }}</a>
		</div>
	</div>
	<div class="sg-divider-none"></div>
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			扫一扫，添加客服微信号：
		</div>
	</div>
	@if($two_code != null)
		<div class="row">
			<div class="col-xs-12 col-sm-12" align="center">
				<img src="{{ $two_code->val }}">
			</div>
		</div>
	@endif
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#after-sale').on('click', function() {
		window.location = "{{ route('orders.after.sale') }}";
	});
});
</script>
@endsection