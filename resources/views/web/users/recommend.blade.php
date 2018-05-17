@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.recommend {
		font-size: 1.3em;
	}
	.recommend-content {
		font-size: 0.8em;
	}
</style>
@endsection

@section('content')
<div class="container sg-container">
	<div class="recommend">
		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<div class="sg-centered">推荐有礼</div>
			</div>
		</div>
		<div class="recommend-content">
			@foreach($recommends as $recommend)
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						<strong>{{ $recommend->name }}</strong>
						<p>{{ $recommend->info }}</p>
					</div>
				</div>
			@endforeach
		</div>
	</div>
	<div class="recommend">
		<div class="row">
			<div class="col-xs-4 col-sm-4">我的状态</div>
			<div class="col-xs-8 col-sm-8">
				{{ Auth::user()->recommend == 0 ? '未开通' : '已开通了 ' . $my_recommend->name }}
			</div>
		</div>
		@if(Auth::user()->recommend != 0)
			<div class="row">
				<div class="col-xs-12 col-sm-12" align="center">
					<h3 class="sg-centered">我的推荐二维码</h3>
					{!! QrCode::size(200)->generate(route('register', ['id' => Auth::user()->id])) !!}
				</div>
			</div>
			<div class="row">
				<div class="col-xs-4 col-sm-4 col-xs-offset-2 col-sm-offset-2">
					所得奖励
				</div>
				<div class="col-xs-4 col-sm-4">
					<div class="pull-right">{{ Auth::user()->prize_money }}</div>
				</div>
			</div>
			<div class="sg-divider-none"></div>
			<div class="row">
				<div class="col-xs-6 col-sm-6 col-xs-offset-3 col-sm-offset-3 sg-centered">
					<a href="{{ route('orders.award') }}">奖励订单</a>
				</div>
			</div>
			<div class="sg-divider-light"></div>
			<div class="row">
				<div class="col-xs-6 col-sm-6 col-xs-offset-3 col-sm-offset-3 sg-centered">
					<a href="{{ route('money.back.index') }}">提现</a>
				</div>
			</div>
		@endif
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	
});
</script>
@endsection