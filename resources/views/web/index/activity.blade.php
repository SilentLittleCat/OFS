@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.activity-container {
		margin: 50px 10px;
	}
	.food-kind {
		font-weight: bold;
		font-size: 1.3em;
	}
	.sg-hide {
		display: none;
	}
</style>
@endsection

@section('content')
<div class="container activity-container">
	<form class="form-horizontal" role="form" id="acts_form" method="POST" action="{{ route('orders.generate') }}">
		{{ csrf_field() }}

		<input class="form-control input-sm sg-hide" type="text" value="1" name="method">
		<input class="form-control input-sm sg-hide" type="text" value="1" name="type" id="food-type">

		@if($man_acts != null && $man_acts->count() != 0 && $man_food != null)
		<div class="form-group">
			<div class="col-xs-6 col-sm-6">
				<div class="row">
					<img src="{{ $man_food->poster }}" class="img-responsive img-rounded" alt="餐类图片">
				</div>
				<div class="row">
					<span class="food-kind">男士餐</span>
					<span class="sg-money">￥{{ $man_food_price }}</span>
				</div>
				<div class="row">
					简介
					<p>{{ $man_food->info }}</p>
				</div>
			</div>
			<div class="col-xs-5 col-sm-5 col-xs-offset-1 col-sm-offset-1">
				<div class="row">
					选择活动
				</div>
				<div class="row">
					@foreach($man_acts as $man_act)
						<div class="radio">
							<label>
								<input type="radio" name="act_id" value="{{ $man_act->id }}" data-money="{{ ($man_act->times * $man_food_price - $man_act->money) }}" class="radio-item" data-type="1">
								{{ $man_act->times . '次（' . round(($man_act->times * $man_food_price - $man_act->money) / ($man_act->times * $man_food_price), 2) * 100 . '折）' }}
							</label>
						</div>
					@endforeach
				</div>
			</div>
		</div>
		<div class="sg-divider"></div>
		@endif
		@if($woman_acts != null && $woman_acts->count() != 0 && $woman_food != null)
		<div class="form-group">
			<div class="col-xs-6 col-sm-6">
				<div class="row">
					<img src="{{ $woman_food->poster }}" class="img-responsive img-rounded" alt="餐类图片">
				</div>
				<div class="row">
					<span class="food-kind">女士餐</span>
					<span class="sg-money">￥{{ $woman_food_price }}</span>
				</div>
				<div class="row">
					简介
					<p>{{ $woman_food->info }}</p>
				</div>
			</div>
			<div class="col-xs-5 col-sm-5 col-xs-offset-1 col-sm-offset-1">
				<div class="row">
					选择活动
				</div>
				<div class="row">
					@foreach($woman_acts as $woman_act)
						<div class="radio">
							<label>
								<input type="radio" name="act_id" value="{{ $woman_act->id }}" data-money="{{ ($woman_act->times * $woman_food_price - $woman_act->money) }}" class="radio-item" data-type="2">
								{{ $woman_act->times . '次（' . round(($woman_act->times * $woman_food_price - $woman_act->money) / ($woman_act->times * $woman_food_price), 2) * 100 . '折）' }}
							</label>
						</div>
					@endforeach
				</div>
			</div>
		</div>
		<div class="sg-divider"></div>
		@endif
		@if($work_acts != null && $work_acts->count() != 0 && $work_food != null)
		<div class="form-group">
			<div class="col-xs-6 col-sm-6">
				<div class="row">
					<img src="{{ $work_food->poster }}" class="img-responsive img-rounded" alt="餐类图片">
				</div>
				<div class="row">
					<span class="food-kind">工作餐</span>
					<span class="sg-money">￥{{ $work_food_price }}</span>
				</div>
				<div class="row">
					简介
					<p>{{ $work_food->info }}</p>
				</div>
			</div>
			<div class="col-xs-5 col-sm-5 col-xs-offset-1 col-sm-offset-1">
				<div class="row">
					选择活动
				</div>
				<div class="row">
					@foreach($work_acts as $work_act)
						<div class="radio">
							<label>
								<input type="radio" name="act_id" value="{{ $work_act->id }}" data-money="{{ ($work_act->times * $work_food_price - $work_act->money) }}" class="radio-item" data-type="3">
								{{ $work_act->times . '次（' . round(($work_act->times * $work_food_price - $work_act->money) / ($work_act->times * $work_food_price), 2) * 100 . '折）' }}
							</label>
						</div>
					@endforeach
				</div>
			</div>
		</div>
		<div class="sg-divider"></div>
		@endif
		<div class="form-group">
			<div class="col-xs-8 col-sm-8 col-xs-offset-2 col-sm-offset-2 sg-centered">
				待支付&nbsp;&nbsp;&nbsp;&nbsp;<span class="sg-money" id="need-pay-money">￥ 0</span>
			</div>
		</div>
		<div class="form-group">
			<div class="col-xs-8 col-sm-8 col-xs-offset-2 col-sm-offset-2">
				<div class="weui-btn weui-btn_primary" id="pay-btn">立即支付</div>
			</div>
		</div>
	</form>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	
	@if($errors->has('pay_error'))
		$.toptip("余额不足！请充值后再购买！", 'error');
	@endif

	$('#acts_form').on('click', '.radio-item', function() {
		$('#need-pay-money').text('￥ ' + $(this).attr('data-money'));
		$('#food-type').val($(this).attr('data-type'));
		$(':radio').removeClass('radio-selected');
		$(this).addClass('radio-selected');
	});

	$('#pay-btn').on('click', function() {
		num = $('.radio-selected').length;
		if(num != 0) {
			$('#acts_form').submit();
		}
	});
});
</script>
@endsection