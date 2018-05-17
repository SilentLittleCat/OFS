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
			<a class="weui-navbar__item" href="#wait-look-tab">
				待审核
			</a>
			<a class="weui-navbar__item" href="#back-money-tab">
				已退款
			</a>
			<a class="weui-navbar__item" href="#not-pass-tab">
				审核不过
			</a>
		</div>
		<div class="weui-tab__bd" id="sg-tab">
			<div id="all-tab" class="weui-tab__bd-item weui-tab__bd-item--active">
				@if($orders == null || $orders->count() == 0)
					<div class="weui-flex">
						<div class="weui-flex__item sg-centered">暂无售后订单记录！</div>
					</div>
				@else
					@foreach($orders as $order)
						<div class="order" data-id="{{ $order->id }}">
							<div class="row">
								<div class="col-xs-8 col-sm-8">订单号：{{ sprintf('%013s', $order->order_id) }}</div>
								<div class="col-xs-4 col-sm-4">
									@if($order->status == 0)
										<span class="label label-info pull-right">待审核</span>
									@elseif($order->status == 1)
										<span class="label label-success pull-right">已退款</span>
									@else
										<span class="label label-danger pull-right">审核不过</span>
									@endif
								</div>
							</div>
							<div class="sg-divider-light"></div>
							<div class="row">
								<div class="col-xs-4 col-sm-4 sg-food-kind">{{ $order->type == 1 ? '男士餐' : ($order->type == 2 ? '女士餐' : '工作餐') }}</div>
								<div class="col-xs-4 col-sm-4 pull-right sg-money-sm">{{ '￥ ' . $order->price }}</div>
							</div>
							<div class="sg-divider-bold"></div>
						</div>
					@endforeach
				@endif
			</div>
			<div id="wait-look-tab" class="weui-tab__bd-item">
				@if($wait_look == null || $wait_look->count() == 0)
					<div class="weui-flex">
						<div class="weui-flex__item sg-centered">暂无待审核售后订单记录！</div>
					</div>
				@else
					@foreach($wait_look as $order)
						<div class="order" data-id="{{ $order->id }}">
							<div class="row">
								<div class="col-xs-8 col-sm-8">订单号：{{ sprintf('%013s', $order->order_id) }}</div>
								<div class="col-xs-4 col-sm-4">
									<span class="label label-info pull-right">待审核</span>
								</div>
							</div>
							<div class="sg-divider-light"></div>
							<div class="row">
								<div class="col-xs-4 col-sm-4 sg-food-kind">{{ $order->type == 1 ? '男士餐' : ($order->type == 2 ? '女士餐' : '工作餐') }}</div>
								<div class="col-xs-4 col-sm-4 pull-right sg-money-sm">{{ '￥ ' . $order->price }}</div>
							</div>
							<div class="sg-divider-bold"></div>
						</div>
					@endforeach
				@endif
			</div>
			<div id="back-money-tab" class="weui-tab__bd-item">
				@if($back_money == null || $back_money->count() == 0)
					<div class="weui-flex">
						<div class="weui-flex__item sg-centered">暂无已退款售后订单记录！</div>
					</div>
				@else
					@foreach($back_money as $order)
						<div class="order" data-id="{{ $order->id }}">
							<div class="row">
								<div class="col-xs-8 col-sm-8">订单号：{{ sprintf('%013s', $order->order_id) }}</div>
								<div class="col-xs-4 col-sm-4">
									<span class="label label-success pull-right">已退款</span>
								</div>
							</div>
							<div class="sg-divider-light"></div>
							<div class="row">
								<div class="col-xs-4 col-sm-4 sg-food-kind">{{ $order->type == 1 ? '男士餐' : ($order->type == 2 ? '女士餐' : '工作餐') }}</div>
								<div class="col-xs-4 col-sm-4 pull-right sg-money-sm">{{ '￥ ' . $order->price }}</div>
							</div>
							<div class="sg-divider-bold"></div>
						</div>
					@endforeach
				@endif
			</div>
			<div id="not-pass-tab" class="weui-tab__bd-item">
				@if($not_pass == null || $not_pass->count() == 0)
					<div class="weui-flex">
						<div class="weui-flex__item sg-centered">暂无审核不过售后订单记录！</div>
					</div>
				@else
					@foreach($not_pass as $order)
						<div class="order" data-id="{{ $order->id }}">
							<div class="row">
								<div class="col-xs-8 col-sm-8">订单号：{{ sprintf('%013s', $order->order_id) }}</div>
								<div class="col-xs-4 col-sm-4">
									<span class="label label-danger pull-right">审核不过</span>
								</div>
							</div>
							<div class="sg-divider-light"></div>
							<div class="row">
								<div class="col-xs-4 col-sm-4 sg-food-kind">{{ $order->type == 1 ? '男士餐' : ($order->type == 2 ? '女士餐' : '工作餐') }}</div>
								<div class="col-xs-4 col-sm-4 pull-right sg-money-sm">{{ '￥ ' . $order->price }}</div>
							</div>
							<div class="sg-divider-bold"></div>
						</div>
					@endforeach
				@endif
			</div>
		</div>
	</div>
</div>
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
		window.location = "{{ route('orders.after.sale') }}" + '/' + $(this).attr('data-id');
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