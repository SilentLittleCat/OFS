@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.order-status {
		width: 100%;
		font-size: 1.1em;
		padding: 10px;
		height: 50px;
		font-weight: bold;
		color: white;
	}
	.order-info {
		margin-top: 20px;
	}
	.order-header {
		font-weight: bold;
		font-size: 1.2em;
	}
	.food-kind-row {
		font-weight: bold;
		font-size: 1.1em;
		margin-top: 15px;
		margin-bottom: 15px;
	}
	.order-bottom {
		position: fixed;
		bottom: 0px;
		background-color: #888;
		color: white;
		width: 100%;
		padding: 10px;
	}

</style>
@endsection

@section('content')
<div class="container sg-container">
	<div class="row">
		<div class="col-xs-8 col-sm-8 col-xs-offset-2 col-sm-offset-2">
			@if($order->status == 0)
				<div class="order-status label-info sg-centered">
					待审核
				</div>
			@elseif($order->status == 1)
				<div class="order-status label-success sg-centered">
					已退款
				</div>
			@else
				<div class="order-status label-danger sg-centered">
					审核通过
				</div>
			@endif
		</div>
	</div>
	<div class="order-info">
		<div class="row">
			<div class="col-xs-4 col-sm-4 order-header">订单详情</div>
		</div>
		<div class="sg-divider-light"></div>
		<div class="row">
			<div class="col-xs-4 col-sm-4">订单编号</div>
			<div class="col-xs-8 col-sm-8">{{ sprintf('%012s', $order->order_id) }}</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-sm-4">配送时间</div>
			<div class="col-xs-8 col-sm-8">{{ $order->time }}</div>
		</div>
		<div class="row">
			<div class="col-xs-4 col-sm-4">{{ $order->name }}</div>
			<div class="col-xs-8 col-sm-8">{{ $order->tel }}</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12">{{ $order->address }}</div>
		</div>
		<div class="sg-divider-light"></div>
		<div class="row food-kind-row">
			<div class="col-xs-8 col-sm-8">{{  ($order->type == 1 ? '男士餐' : ($order->type == 2 ? '女士餐' : '工作餐')) }}</div>
			<div class="col-xs-4 col-sm-4 pull-right sg-money-sm">{{ '￥ ' . $order->price }}</div>
		</div>
		<div class="sg-divider-light"></div>
<!-- 		<div class="row">
			<div class="col-xs-4 col-sm-4 order-header">配送详情</div>
		</div>
		<div class="sg-divider-light"></div>
		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<i class="fa fa-user fa-lg" aria-hidden="true"></i>
				&nbsp;&nbsp;{{ $order->real_name . ' ' . $order->tel }}
			</div>
		</div>
		<div class="sg-divider-none"></div>
		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<i class="fa fa-map-marker fa-lg" aria-hidden="true"></i>
				&nbsp;&nbsp;&nbsp;{{ $order->address }}
			</div>
		</div> -->
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {

});
</script>
@endsection