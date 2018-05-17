@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.coupon-range {
		color: red;
	}
</style>
@endsection

@section('content')
<div class="container sg-container">
	@if($coupons->count() == 0)
		<div class="col-xs-12 col-sm-12" style="text-align: center; margin-top: 20px;">暂无优惠券！</div>
	@else
		<div class="coupons">
			@foreach($coupons as $coupon)
				<div class="row">
					<div class="col-xs-3 col-sm-3" style="text-align: center">
						<i class="fa fa-tag fa-3x" aria-hidden="true" style="line-height: 100%"></i>
					</div>
					<div class="col-xs-9 col-sm-9">
						<div class="row">
							<div class="col-xs-8 col-sm-8 sg-strong">
								{{ $coupon->name }}
							</div>
							@if($coupon->pivot->status == 0)
								<div class="col-xs-4 col-sm-4">
									<a href="{{ route('index') }}">去使用</a>
								</div>
							@endif
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-12 coupon-range">
								@if($coupon->type == 0)
									适用范围：通用范围
								@elseif($coupon->type == 1)
									适用范围：男士餐
								@elseif($coupon->type == 2)
									适用范围：女士餐
								@elseif($coupon->type == 3)
									适用范围：工作餐
								@endif
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-12 coupon-range">{{ '详情：消费满' . $coupon->condition . '元减' . $coupon->money . '元' }}</div>
						</div>
						<div class="row">
							<div class="col-xs-12 col-sm-12 sg-time">
								{{ $coupon->begin_time . '——' . $coupon->end_time }}
							</div>
						</div>
					</div>
				</div>
				<div class="sg-divider-light"></div>
			@endforeach
		</div>
	@endif
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	
});
</script>
@endsection