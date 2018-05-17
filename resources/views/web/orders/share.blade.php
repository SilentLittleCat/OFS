@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.wechat-info-margin {
		margin-top: 20px;
	}
</style>
@endsection

@section('content')
<div class="container sg-container">
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<img src="{{ $work_food_img->url }}" class="img-responsive" alt="餐类图片" id="food-img">
		</div>
	</div>
	<div class="sg-divider-none"></div>
	<div class="row">
		<div class="col-xs-10 col-sm-10 col-xs-offset-1 col-sm-offset-1 sg-light-1">
			您的好友&nbsp;&nbsp;<strong style="color: black">{{ $order->wechat_name }}</strong>&nbsp;&nbsp;邀请您一同预订以下的工作餐
		</div>
	</div>
	<div class="sg-divider-none"></div>
	<div class="row">
		<div class="col-xs-4 col-sm-4 col-xs-offset-1 col-sm-offset-1 sg-light-1">
			订单详情
		</div>
		<div class="col-xs-6 col-sm-6">{!! $food_info !!}</div>
	</div>
	<div class="row">
		<div class="col-xs-10 col-sm-10 col-xs-offset-1 col-sm-offset-1 sg-light-1">
			已参与预定人
		</div>
	</div>
	@foreach($order_shares as $order_share)
		<div class="row">
			<div class="col-xs-3 col-sm-3 col-xs-offset-1 col-sm-offset-1 fa-3x">
				<i class="fa fa-user-circle" aria-hidden="true"></i>
			</div>
			<div class="col-xs-3 col-sm-3 wechat-info-margin">
				{{ $order_share->wechat_name }}
			</div>
		</div>
	@endforeach
	@if($is_confirm == 0)
		<div class="row">
			<div class="col-xs-6 col-sm-6 col-xs-offset-3 col-sm-offset-3">
				<div class="btn btn-sm btn-block btn-primary" id="btn-confirm">确认</div>
			</div>
		</div>
	@endif
</div>
<div class="modal fade" id="share-modal" tabindex="-1" role="dialog" aria-labelledby="share-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="share-modal-label">
					确认加入该订单吗？
				</h4>
			</div>
			<div class="modal-body">
				{!! $food_info !!}
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-success" id="share-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="money-modal" tabindex="-1" role="dialog" aria-labelledby="money-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">
					余额不足！请充值后再购买！
				</h4>
			</div>
			<div class="modal-body">

			</div>
		</div>
	</div>
</div>
@if(isset($errors) && !$errors->isEmpty() && $errors->has('pay_error'))

<script type="text/javascript">
$(function() {
	info = "{{ $errors->first('pay_error') }}";
	$('#money-modal .modal-body').text(info);
	$('#money-modal').modal('show');
});
</script>
    
@endif
<form style="display: none" method="POST" action="{{ route('orders.confirm', ['id' => $master_order->id]) }}" id="share-form">
	{{ csrf_field() }}
</form>

@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#btn-confirm').on('click', function() {
		$('#share-modal').modal('show');
	});

	$('#share-confirm-btn').on('click', function() {
		$('#share-form').submit();
	});
});
</script>
@endsection