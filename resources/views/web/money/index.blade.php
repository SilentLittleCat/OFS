@extends('web.layouts.master')

@section('header')
<style type="text/css">
	
</style>
@endsection

@section('content')
<div class="container sg-container">
	<div class="row">
		<form class="form-horizontal" method="POST" action="{{ route('money.pay') }}">
			{{ csrf_field() }}

			<div class="form-group">
				<label class="col-xs-4 col-sm-4 control-label">订单ID</label>
				<div class="col-xs-8 col-sm-8">
					<input type="text" name="order_id" class="form-control" readonly value="{{ $order->id }}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 col-sm-4 control-label">预定餐类</label>
				<div class="col-xs-8 col-sm-8">
					<input type="text" name="order_id" class="form-control" readonly value="{{ $order->num . '份' . ($order->type == 1 ? '男士餐' : ($order->type == 2 ? '女士餐' : '工作餐')) }}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 col-sm-4 control-label">原价</label>
				<div class="col-xs-8 col-sm-8">
					<input type="text" name="order_id" class="form-control" readonly value="{{ $origin_money }}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 col-sm-4 control-label">需支付</label>
				<div class="col-xs-8 col-sm-8">
					<input type="text" name="order_id" class="form-control" readonly value="{{ $real_need_pay }}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 col-sm-4 control-label">账户余额</label>
				<div class="col-xs-8 col-sm-8">
					<input type="text" name="order_id" class="form-control" readonly value="{{ $remain_money }}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 col-sm-4 control-label">优惠券</label>
				<div class="col-xs-8 col-sm-8">
					<input type="text" value="{{ $coupon == null ? null : $coupon->id }}" style="display: none" name="coupon_id">
					<input type="text" name="order_id" class="form-control" readonly value="{{ $coupon == null ? '无优惠券' : $coupon->name }}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 col-sm-4 control-label">活动</label>
				<div class="col-xs-8 col-sm-8">
					<input type="text" value="{{ $act == null ? null : $act->id }}" style="display: none" name="act_id">
					<input type="text" name="order_id" class="form-control" readonly value="{{ $act == null ? '未参加活动' : $act->name }}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-xs-4 col-sm-4 control-label">推荐人</label>
				<div class="col-xs-8 col-sm-8">
					<input type="text" name="order_id" class="form-control" readonly value="{{ $recommend == null ? '无推荐人' : $recommend }}">
				</div>
			</div>
			<div class="form-group">
				<div class="col-xs-8 col-sm-8 col-xs-offset-4 col-sm-offset-4">
					<button class="btn btn-success" type="submit">确认支付</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	
});
</script>
@endsection