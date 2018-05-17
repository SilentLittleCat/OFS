@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.orders {
		margin-top: 20px;
	}
	.order {
		padding: 4px;
	}
	.sg-hide {
		display: none;
	}
	.weui-tab .weui-navbar {
		position: fixed;
		top: 50px;
		width: 100%;
	}
	.sg-container {
		margin: 50px 0px;
	}
	.weui-navbar__item.weui-bar__item--on {
		color: #1abc9c;
		background-color: transparent;
		border-bottom: 3px solid #1abc9c;
	}
	#sg-tab {
		margin-top: 70px;
		margin-left: 10px;
		margin-right: 10px;
	}
	.weui-navbar a {
		text-decoration: none;
	}
</style>
@endsection

@section('content')
<div class="sg-container">
	<div class="weui-tab">
		<div class="weui-navbar">
			<a class="weui-navbar__item weui-bar__item--on" href="#all-tab">
				全部
			</a>
			<a class="weui-navbar__item" href="#wait-pay-tab">
				待支付
			</a>
			<a class="weui-navbar__item" href="#go-on-tab">
				进行中
			</a>
			<a class="weui-navbar__item" href="#complete-tab">
				已完成
			</a>
		</div>
		<div class="weui-tab__bd" id="sg-tab">
			<div id="all-tab" class="weui-tab__bd-item weui-tab__bd-item--active">
				@if($orders == null || $orders->count() == 0)
					<div class="weui-flex">
						<div class="weui-flex__item sg-centered">暂无订单记录！</div>
					</div>
				@else
					@foreach($orders as $order)
						<div class="order" data-id="{{ $order->id }}" data-status="{{ $order->status }}" data-pay-status="{{ $order->pay_status }}">
							<div class="row">
								<div class="col-xs-8 col-sm-8">订单号：{{ sprintf('%013s', $order->id) }}</div>
								<div class="col-xs-4 col-sm-4">
									@if($order->pay_status == 0)
										<span class="label label-warning pull-right">待支付</span>
									@elseif($order->status == 0)
										<span class="label label-info pull-right">进行中</span>
									@else
										<span class="label label-success pull-right">已完成</span>
									@endif
								</div>
							</div>
							<div class="sg-divider-light"></div>
							<div class="row">
								<div class="col-xs-4 col-sm-4 sg-food-kind">{{ $order->type == 1 ? '男士餐' : ($order->type == 2 ? '女士餐' : '工作餐') }}</div>
								<div class="col-xs-4 col-sm-4 pull-right sg-money-sm">{{ '￥ ' . $order->money }}</div>
							</div>
							<div class="row">
								<div class="col-xs-4 col-sm-4">{{ '已定' . $order->days . '天' }}</div>
							</div>
							<div class="sg-divider-bold"></div>
						</div>
					@endforeach
				@endif
			</div>
			<div id="wait-pay-tab" class="weui-tab__bd-item">
				@if($wait_pays == null || $wait_pays->count() == 0)
					<div class="weui-flex">
						<div class="weui-flex__item sg-centered">暂无未支付订单记录！</div>
					</div>
				@else
					@foreach($wait_pays as $order)
						<div class="order" data-id="{{ $order->id }}" data-status="{{ $order->status }}" data-pay-status="{{ $order->pay_status }}">
							<div class="row">
								<div class="col-xs-8 col-sm-8">订单号：{{ sprintf('%013s', $order->id) }}</div>
								<div class="col-xs-4 col-sm-4">
									<span class="label label-warning pull-right">待支付</span>
								</div>
							</div>
							<div class="sg-divider-light"></div>
							<div class="row">
								<div class="col-xs-4 col-sm-4 sg-food-kind">{{ $order->type == 1 ? '男士餐' : ($order->type == 2 ? '女士餐' : '工作餐') }}</div>
								<div class="col-xs-4 col-sm-4 pull-right sg-money-sm">{{ '￥ ' . $order->money }}</div>
							</div>
							<div class="row">
								<div class="col-xs-4 col-sm-4">{{ '已定' . $order->days . '天' }}</div>
							</div>
							<div class="sg-divider-bold"></div>
						</div>
					@endforeach
				@endif
			</div>
			<div id="go-on-tab" class="weui-tab__bd-item">
				@if($go_ons == null || $go_ons->count() == 0)
					<div class="weui-flex">
						<div class="weui-flex__item sg-centered">暂无进行中订单记录！</div>
					</div>
				@else
					@foreach($go_ons as $order)
						<div class="order" data-id="{{ $order->id }}" data-status="{{ $order->status }}" data-pay-status="{{ $order->pay_status }}">
							<div class="row">
								<div class="col-xs-8 col-sm-8">订单号：{{ sprintf('%013s', $order->id) }}</div>
								<div class="col-xs-4 col-sm-4">
									<span class="label label-info pull-right">进行中</span>
								</div>
							</div>
							<div class="sg-divider-light"></div>
							<div class="row">
								<div class="col-xs-4 col-sm-4 sg-food-kind">{{ $order->type == 1 ? '男士餐' : ($order->type == 2 ? '女士餐' : '工作餐') }}</div>
								<div class="col-xs-4 col-sm-4 pull-right sg-money-sm">{{ '￥ ' . $order->money }}</div>
							</div>
							<div class="row">
								<div class="col-xs-4 col-sm-4">{{ '已定' . $order->days . '天' }}</div>
							</div>
							<div class="sg-divider-bold"></div>
						</div>
					@endforeach
				@endif
			</div>
			<div id="complete-tab" class="weui-tab__bd-item">
				@if($completes == null || $completes->count() == 0)
					<div class="weui-flex">
						<div class="weui-flex__item sg-centered">暂无已完成订单记录！</div>
					</div>
				@else
					@foreach($completes as $order)
						<div class="order" data-id="{{ $order->id }}" data-status="{{ $order->status }}" data-pay-status="{{ $order->pay_status }}">
							<div class="row">
								<div class="col-xs-8 col-sm-8">订单号：{{ sprintf('%013s', $order->id) }}</div>
								<div class="col-xs-4 col-sm-4">
									<span class="label label-success pull-right">已完成</span>
								</div>
							</div>
							<div class="sg-divider-light"></div>
							<div class="row">
								<div class="col-xs-4 col-sm-4 sg-food-kind">{{ $order->type == 1 ? '男士餐' : ($order->type == 2 ? '女士餐' : '工作餐') }}</div>
								<div class="col-xs-4 col-sm-4 pull-right sg-money-sm">{{ '￥ ' . $order->money }}</div>
							</div>
							<div class="row">
								<div class="col-xs-4 col-sm-4">{{ '已定' . $order->days . '天' }}</div>
							</div>
							<div class="sg-divider-bold"></div>
						</div>
					@endforeach
				@endif
			</div>
		</div>
	</div>
</div>
<!-- <div class="container sg-container">
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<div class="weui-cell weui-cell_select">
				<div class="weui-cell__bd">
					<select id="orders-filter" class="weui-select">
						<option value="all" selected>全部</option>
						<option value="wait-pay">待支付</option>
						<option value="completed">已完成</option>
						<option value="cancel">已取消</option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="orders">
		@foreach($orders as $order)
			<div class="order" data-id="{{ $order->id }}" data-status="{{ $order->status }}" data-pay-status="{{ $order->pay_status }}">
				<div class="row">
					<div class="col-xs-8 col-sm-8">订单号：{{ sprintf('%013s', $order->id) }}</div>
					<div class="col-xs-4 col-sm-4">
						@if($order->pay_status == 0)
							<span class="label label-warning pull-right">待支付</span>
						@elseif($order->status == 1)
							<span class="label label-success pull-right">已完成</span>
						@elseif($order->status == 2)
							<span class="label label-danger pull-right">已取消</span>
						@elseif($order->status == 0)
							<span class="label label-info pull-right">进行中</span>
						@endif
					</div>
				</div>
				<div class="sg-divider-light"></div>
				<div class="row">
					<div class="col-xs-4 col-sm-4 sg-food-kind">{{ $order->type == 1 ? '男士餐' : ($order->type == 2 ? '女士餐' : '工作餐') }}</div>
					<div class="col-xs-4 col-sm-4 pull-right sg-money-sm">{{ '￥ ' . $order->money }}</div>
				</div>
				<div class="row">
					<div class="col-xs-4 col-sm-4">{{ '已定' . count(explode(',', $order->dates)) . '天' }}</div>
					<div class="col-xs-8 col-sm-8">
						<div class="btn-group pull-right">
							@if($order->type == 3 && $order->method == 0 && $order->pay_status != 1 && $order->use_coupon == 0 && $order->use_remain_times == 0)
								<div class="btn btn-xs btn-primary share-order" data-id="{{ $order->id }}">分享好友</div>
								<div class="btn btn-xs btn-info share-info" data-id="{{ $order->id }}">分享详情</div>
							@endif
							<div class="btn btn-xs btn-success order-info" data-id="{{ $order->id }}">订单详情</div>
						</div>
					</div>
				</div>
				<div class="sg-divider-bold"></div>
			</div>
		@endforeach
	</div>
</div> -->
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('.orders').on('click', '.order-info', function() {
		window.location = "{{ route('orders.index') }}" + '/' + $(this).attr('data-id');
	}).on('click', '.share-order', function() {
		window.location = "{{ route('orders.share.code') }}" + '?id=' + $(this).attr('data-id');
	}).on('click', '.share-info', function() {
		window.location = "{{ route('orders.share') }}" + '?id=' + $(this).attr('data-id');
	});

	$('#sg-tab').on('click', '.order', function() {
		window.location = "{{ route('orders.index') }}" + '/' + $(this).attr('data-id');
	});

	function updateOrdersShow() {
		val = $('#orders-filter').val();
		if(val == 'all') {
			$('.order').removeClass('sg-hide');
		} else if(val == 'wait-pay') {
			$('.order').addClass('sg-hide');
			$('.order[data-pay-status=0]').removeClass('sg-hide');
		} else if(val == 'completed') {
			$('.order').addClass('sg-hide');
			$('.order[data-status=1]').removeClass('sg-hide');
		} else if(val == 'cancel') {
			$('.order').addClass('sg-hide');
			$('.order[data-status=2]').removeClass('sg-hide');
		}
	}

	$('#orders-filter').change(function() {
		updateOrdersShow();
	});
});
</script>
@endsection