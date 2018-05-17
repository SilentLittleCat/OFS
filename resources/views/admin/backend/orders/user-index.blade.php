@extends('admin.layout')

@section('header')
<style type="text/css">
	
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
					<h5>订单记录</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" method="POST" action="{{ U('Backend/Order/userIndex', ['id' => $user->id, 'search' => true]) }}">
							{{ csrf_field() }}

							<div class="form-group">
								<div class="col-sm-2 col-sm-offset-1">
									<select class="form-control" name="type">
										@if(isset($info['type']))
											<option value="0" {{ $info['type'] == 0 ? 'selected' : '' }}>所有</option>
											<option value="1" {{ $info['type'] == 1 ? 'selected' : '' }}>男士餐</option>
											<option value="2" {{ $info['type'] == 2 ? 'selected' : '' }}>女士餐</option>
											<option value="3" {{ $info['type'] == 3 ? 'selected' : '' }}>工作餐</option>
										@else
											<option value="0" selected>所有</option>
											<option value="1">男士餐</option>
											<option value="2">女士餐</option>
											<option value="3">工作餐</option>
										@endif
									</select>
								</div>
								<div class="col-sm-5">
									<div class="input-group">
										@if(isset($info['begin_time']))
											<input type="text" name="begin_time" class="form-control" placeholder="起始时间" id="begin_time" value="{{ $info['begin_time'] }}">
										@else
											<input type="text" name="begin_time" class="form-control" placeholder="起始时间" id="begin_time">
										@endif
										<span class="input-group-addon">到</span>
										@if(isset($info['end_time']))
											<input type="text" name="end_time" class="form-control" placeholder="终止时间" id="end_time" value="{{ $info['end_time'] }}">
										@else
											<input type="text" name="end_time" class="form-control" placeholder="终止时间" id="end_time">
										@endif
									</div>
								</div>
								<div class="col-sm-4">
									<button class="btn btn-sm btn-primary" type="submit">筛选</button>
								</div>
							</div>
						</form>
					</div>
                    <table class="table table-striped table-bordered table-hover dataTable" id="user-order-table">
                        <thead>
                            <tr>
                            	<th class="sorting" data-sort="id">订单号</th>
                                <th>预定产品</th>
                                <th>订单金额</th>
                                <th>付款状态</th>
                                <th>订单状态</th>
                                <th>下单人</th>
                                <th>下单人手机</th>
                                <th class="sorting" data-sort="created_at">下单时间</th>
                                <th>查看</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($orders) == 0)
                                <tr>
                                    <td colspan="9" class="sg-centered">暂无订单记录！</td>
                                </tr>
                            @else
                                @foreach($orders as $order)
                                    <tr>
                                    	<td>{{ sprintf("%013d", $order->id) }}</td>
                                        <td>
                                        	@if($order->type == 1)
                                        		{{ '男士餐：' . $order->num . '份' }}
                                        	@elseif($order->type == 2)
                                        		{{ '女士餐：' . $order->num . '份' }}
                                        	@elseif($order->type == 3)
                                        		{{ '工作餐：' . $order->num . '份' }}
                                        	@endif
                                        </td>
                                        <td>{{ $order->money }}</td>
                                        @if($order->pay_status == 0)
											<td style="color: #d8534c">未支付</td>
                                        @else
                                        	<td style="color: #57b658">已支付</td>
                                        @endif
                                        @if($order->status == 0)
											<td style="color: #f2ad50">进行中</td>
                                        @else
                                        	<td style="color: #57b658">已完成</td>
                                        @endif
                                        <td>{{ $order->wechat_name }}</td>
                                        <td>{{ $order->tel }}</td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>
                                        	<div class="btn-group">
                                        		<div class="btn btn-sm btn-success send-record" data-order-id="{{ $order->id }}" data-id="{{ $order->user_id }}">配送记录</div>
                                        		<div class="btn btn-sm btn-warning btn-pay {{ $order->pay_status == 1 ? 'disabled' : '' }}" data-id="{{ $order->id }}">确认支付</div>
                                        	</div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="dataTables_info" id="DataTables_Table_0_info"
                                role="alert" aria-live="polite" aria-relevant="all">每页{{ $orders->count() }}条，共{{ $orders->lastPage() }}页，总{{ $orders->total() }}条。</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                {!! $orders->setPath('')->appends(Request::all())->render() !!}
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<form id="pay-form" style="display: none" method="POST">
	{{ csrf_field() }}
</form>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#begin_time').datepicker({
		autoclose: true
	});
	$('#end_time').datepicker({
		autoclose: true
	});

	$('#user-order-table').on('click', '.send-record', function() {
		url = "{{ U('Backend/Order/sendRecord') }}" + '?order_id=' + $(this).attr('data-order-id');
		window.location = url;
	}).on('click', '.btn-pay', function() {
		url = "{{ U('Backend/Order/changePayStatus') }}" + '?status=1&id=' + $(this).attr('data-id');
		$('#pay-form').attr('action', url).submit();
	});
});
</script>
@endsection