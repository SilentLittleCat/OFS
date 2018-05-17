@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.orders {
		margin-top: 20px;
	}
	.order {
		padding: 4px;
	}
	.order:hover {
		background-color: rgba(0, 0, 0, 0.1);
	}
</style>
@endsection

@section('content')
<div class="container sg-container">
	<div class="orders">
		@if($orders->count() == 0)
			<div style="text-align: center; margin-top: 20px;">暂无奖励订单！</div>
		@else
			@foreach($orders as $order)
				<div class="order" data-id="{{ $order->id }}">
					<div class="row">
						<div class="col-xs-8 col-sm-8">订单号：{{ sprintf('%013d', $order->id) }}</div>
						<div class="col-xs-4 col-sm-4">
							<div class="sg-money-sm">￥{{ $order->money }}</div>
						</div>
					</div>
					<div class="sg-divider-light"></div>
					<div class="row">
						<div class="col-xs-4 col-sm-4">获得奖励</div>
						<div class="col-xs-4 col-sm-4 pull-right sg-money-sm">+￥{{ $order->prize_money }}</div>
					</div>
				</div>
				<div class="sg-divider-bold"></div>
			@endforeach
		@endif
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('.orders').on('click', '.order', function() {
		window.location = "{{ route('orders.index') }}" + '/award/' + $(this).attr('data-id');
	});
});
</script>
@endsection