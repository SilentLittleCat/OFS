@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.order-status {
		width: 100%;
		font-size: 1.1em;
		padding: 10px;
		height: 50px;
		font-weight: bold;
	}
	.order-info {
		margin-top: 20px;
	}
	.order-header {
		font-weight: bold;
		font-size: 1.2em;
	}
	.order-title {
		font-weight: bold;
		font-size: 1.5em;
	}
	.food-kind-row {
		font-weight: bold;
		font-size: 1.1em;
		margin-top: 15px;
		margin-bottom: 15px;
	}
</style>
@endsection

@section('content')
<div class="container sg-container">
	<div class="row order-title">
		<div class="col-xs-4 col-sm-4 col-xs-offset-2 col-sm-offset-2">
			<div>订单奖金</div>
		</div>
		<div class="col-xs-4 col-sm-4">
			<div class="sg-money-sm">+￥45</div>
		</div>
	</div>
	<div class="order-info">
		<div class="row">
			<div class="col-xs-4 col-sm-4 order-header">订单详情</div>
		</div>
		<div class="sg-divider-light"></div>
		<div class="row">
			<div class="col-xs-4 col-sm-4">订单编号</div>
			<div class="col-xs-8 col-sm-8">00000000000000001</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-sm-4">下单时间</div>
			<div class="col-xs-8 col-sm-8">2017-8-8</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-sm-4">下单人</div>
			<div class="col-xs-8 col-sm-8">李大宝</div>
		</div>
		<div class="sg-divider-light"></div>
		<div class="row food-kind-row">
			<div class="col-xs-4 col-sm-4">男士餐</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-sm-4">4天</div>
			<div class="col-xs-4 col-sm-4 pull-right sg-money-sm">￥35</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12">7.11 7.12 7.13 7.14</div>
		</div>
		<div class="sg-divider-none"></div>
		<div class="row">
			<div class="col-xs-4 col-sm-4 order-header">配送详情</div>
		</div>
		<div class="sg-divider-light"></div>
		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<i class="fa fa-user fa-lg" aria-hidden="true"></i>
				&nbsp;&nbsp;李大宝先生 13880123123
			</div>
		</div>
		<div class="sg-divider-none"></div>
		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<i class="fa fa-map-marker fa-lg" aria-hidden="true"></i>
				&nbsp;&nbsp;&nbsp;四川省，成都市，金牛区，蜀西路42号
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	
});
</script>
@endsection