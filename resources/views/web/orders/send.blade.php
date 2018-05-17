@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.sg-container {
		margin: 50px 0px;
	}
	.send-item {
		margin: 7px 10px;
		text-align: center;
	}
	.send-item-label.not-send-label {
		background-color: #ff99cc;
	}
	.send-item-label.sending-label {
		background-color: #3399ff;
	}
	.send-item-label.sended-label {
		background-color: #00cc66;
	}
	.send-item-label.back-money-label {
		background-color: red;
	}
	.sg-flex {
		margin-bottom: 5px;
	}
	.weui-btn.sg-button {
		color: #1abc9c;
		background-color: white;
	}
	.weui-btn.sg-button:after {
		border: 3px solid #1abc9c;
	}
	.weui-btn.sg-button:active {
		background-color: #f2f2f2;
		color: #1abc9c;
	}
	.weui-navbar__item.weui-bar__item--on {
		color: #1abc9c;
		background-color: transparent;
		border-bottom: 3px solid #1abc9c;
	}
	.weui-navbar a {
		text-decoration: none;
	}
	.weui-picker-modal.sg-visible {
		opacity: 1;
    	-webkit-transform: translate3d(0,0,0);
    	transform: translate3d(0,0,0);
	}
	#inline-calendar {
		position: fixed;
		bottom: 70px;
		width: 100%;
	}
	.sg-visible {
		opacity: 1;
    	-webkit-transform: translate3d(0,0,0);
    	transform: translate3d(0,0,0);
	}
	.weui-tab .weui-navbar {
		position: fixed;
		top: 50px;
	}
</style>
@endsection

@section('content')
<div class="sg-container">
	<div class="weui-tab">
		<div class="weui-navbar">
			<a class="weui-navbar__item weui-bar__item--on" href="#not-send-tab">
				未配送
			</a>
			<a class="weui-navbar__item" href="#sending-tab">
				进行中
			</a>
			<a class="weui-navbar__item" href="#sended-tab">
				已完成
			</a>
			<a class="weui-navbar__item" href="#back-money-tab">
				已退款
			</a>
		</div>
		<div class="weui-tab__bd" id="sg-tab">
			<div id="not-send-tab" class="weui-tab__bd-item weui-tab__bd-item--active">
				@if($not_send_orders->count() == 0)
					<div class="weui-flex">
						<div class="weui-flex__item sg-centered">暂无配送记录！</div>
					</div>
				@else
					@foreach($not_send_orders as $order)
						<div class="send-item">
							<div class="weui-flex sg-flex">
								<div class="weui-flex__item">{{ $order->time }}</div>
								<div class="weui-flex__item">
									<span class="label label-default send-item-label not-send-label">未配送</span>
								</div>
							</div>
							<div class="weui-flex">
								<div class="weui-flex__item">
									<div class="weui-btn weui-btn_mini weui-btn_primary sg-button change-time" data-id="{{ $order->id }}">更改时间</div>
								</div>
								<div class="weui-flex__item">
									<div class="weui-btn weui-btn_mini weui-btn_primary sg-button change-add" data-id="{{ $order->id }}">更改地址</div>
								</div>
							</div>
						</div>
						<div class="sg-divider-light"></div>
					@endforeach
				@endif
			</div>
			<div id="sending-tab" class="weui-tab__bd-item">
				@if($sending_orders->count() == 0)
					<div class="weui-flex">
						<div class="weui-flex__item sg-centered">暂无进行中记录！</div>
					</div>
				@else
					@foreach($sending_orders as $order)
						<div class="send-item">
							<div class="weui-flex sg-flex">
								<div class="weui-flex__item">{{ $order->time }}</div>
								<div class="weui-flex__item">
									<span class="label label-default send-item-label sending-label">进行中</span>
								</div>
							</div>
<!-- 							<div class="weui-flex">
								<div class="weui-flex__item">
									<div class="weui-btn weui-btn_mini weui-btn_primary sg-button change-time" data-id="{{ $order->id }}">更改时间</div>
								</div>
								<div class="weui-flex__item">
									<div class="weui-btn weui-btn_mini weui-btn_primary sg-button change-add" data-id="{{ $order->id }}">更改地址</div>
								</div>
							</div> -->
						</div>
						<div class="sg-divider-light"></div>
					@endforeach
				@endif
			</div>
			<div id="sended-tab" class="weui-tab__bd-item">
				@if($sended_orders->count() == 0)
					<div class="weui-flex">
						<div class="weui-flex__item sg-centered">暂无已完成记录！</div>
					</div>
				@else
					@foreach($sended_orders as $order)
						<div class="send-item">
							<div class="weui-flex sg-flex">
								<div class="weui-flex__item">{{ $order->time }}</div>
								<div class="weui-flex__item">
									<span class="label label-default send-item-label sended-label">已完成</span>
								</div>
							</div>
<!-- 							<div class="weui-flex">
								<div class="weui-flex__item">
									<div class="weui-btn weui-btn_mini weui-btn_primary sg-button change-time" data-id="{{ $order->id }}">更改时间</div>
								</div>
								<div class="weui-flex__item">
									<div class="weui-btn weui-btn_mini weui-btn_primary sg-button change-add" data-id="{{ $order->id }}">更改地址</div>
								</div>
							</div> -->
						</div>
						<div class="sg-divider-light"></div>
					@endforeach
				@endif
			</div>
			<div id="back-money-tab" class="weui-tab__bd-item">
				@if($back_money_orders->count() == 0)
					<div class="weui-flex">
						<div class="weui-flex__item sg-centered">暂无已退款记录！</div>
					</div>
				@else
					@foreach($back_money_orders as $order)
						<div class="send-item">
							<div class="weui-flex sg-flex">
								<div class="weui-flex__item">{{ $order->time }}</div>
								<div class="weui-flex__item">
									<span class="label label-default send-item-label back-money-label">已退款</span>
								</div>
							</div>
<!-- 							<div class="weui-flex">
								<div class="weui-flex__item">
									<div class="weui-btn weui-btn_mini weui-btn_primary sg-button change-time" data-id="{{ $order->id }}">更改时间</div>
								</div>
								<div class="weui-flex__item">
									<div class="weui-btn weui-btn_mini weui-btn_primary sg-button change-add" data-id="{{ $order->id }}">更改地址</div>
								</div>
							</div> -->
						</div>
						<div class="sg-divider-light"></div>
					@endforeach
				@endif
			</div>
		</div>
	</div>
</div>
<form method="POST" style="display: none;" id="change-time-form">
    {{ csrf_field() }}
</form>
<div id="inline-calendar" class="sg-hide"></div>
<input type="hidden" name="" id="date-picker">
@endsection

@section('footer')
<script type="text/javascript">
$(function() {

	date_picker = null;

	$('#date-picker').calendar({
		value: [],
		closeOnSelect: false,
		container: "#inline-calendar",
		onChange: function (p, values, displayValues) {
			if(values.length >= 1) {
				url = "{{ route('orders.change.time') }}";
				url += '?id=' + $('#change-time-form').attr('data-id') + '&time=' + values[0];
				$('#change-time-form').attr('action', url).submit();
			}
		},
		onDayClick: function (p, dayContainer, year, month, day) {

		},
		onOpen: function (p) {
			
			// p.value = [];
			// p.updateValue();
		},
		onMonthAdd: function (p, monthContainer) {

		}
	});

	$('#sg-tab').on('click', '.change-time', function(event) {
		$('#change-time-form').attr('data-id', $(this).attr('data-id'));
		$('#inline-calendar').slideDown();
		// event.stopPropagation()
		event.stopImmediatePropagation();
	}).on('click', '.change-add', function(event) {
		url = "{{ route('orders.change.add') }}";
		url += '?id=' + $(this).attr('data-id');
		window.location = url;
	});

	$('#sg-tab').on('click', function(event) {
		$('#inline-calendar').slideUp();
	});
});
</script>
@endsection