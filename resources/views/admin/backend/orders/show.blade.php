@extends('admin.layout')

@section('header')
<style type="text/css">
	.user-info-item {
		margin-top: 15px;
		margin-bottom: 15px;
	}
</style>
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-sm-12">
			@if(isset($errors) && !$errors->isEmpty())
	        <div class="alert alert-danger alert-dismissable">
	            <button type="button" class="close" data-dismiss="alert"
	                    aria-hidden="true">
	                &times;
	            </button>
	            @foreach($errors->keys() as $key)
	            	{{ $errors->first($key) }}
	            @endforeach
	        </div>
			@endif

			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>订单详情</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="user-info">
						<div class="row user-info-item">
							<div class="col-sm-2">订单号</div>
							<div class="col-sm-10">{{ sprintf("%013d", $order->id) }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">订单金额</div>
							<div class="col-sm-10 danger-color">{{ $order->money }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">用户微信</div>
							<div class="col-sm-10">{{ $order->wechat_id }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">微信昵称</div>
							<div class="col-sm-10">{{ $order->wechat_name }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">付款状态</div>
							<div class="col-sm-10">
								@if($order->pay_status == 0)
									<div class="danger-color">未支付</div>
                                @else
                                	<div class="success-color">已支付</div>
                                @endif
							</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">订单状态</div>
							<div class="col-sm-10">
								@if($order->status == 0)
									<div class="warning-color">进行中</div>
                                @else
                                	<div class="success-color">已完成</div>
                                @endif
							</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">下单时间</div>
							<div class="col-sm-10">{{ $order->created_at }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">预订产品</div>
							<div class="col-sm-10">
								@if($order->type == 1)
									男士餐：<span>{{ $order->num . '份' }}</span>
								@elseif($order->type == 2)
									女士餐：<span>{{ $order->num . '份' }}</span>
								@elseif($order->type == 3)
									工作餐：<span>{{ $order->num . '份' }}</span>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {

});
</script>
@endsection